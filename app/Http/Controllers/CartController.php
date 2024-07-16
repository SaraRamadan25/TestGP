<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Item;
use App\Models\PromoCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required|array',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'promo_code_id' => 'nullable|exists:promo_codes,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        $items = $request->input('items', []);
        $total = 0;

        foreach ($items as $item) {
            $itemModel = Item::findOrFail($item['item_id']);
            $total += $itemModel->price * $item['quantity'];
        }

        $promoCodeId = $request->input('promo_code_id');
        $totalAfterDiscount = null;

        if ($promoCodeId) {
            $promoCode = PromoCode::findOrFail($promoCodeId);
            $discountPercentage = $promoCode->percentage;
            $discountAmount = ($total * $discountPercentage) / 100;
            $totalAfterDiscount = $total - $discountAmount;
        }

        $cart = Cart::create([
            'user_id' => $user ? $user->id : null,
            'items' => json_encode($items),
            'total' => $total,
            'promo_code_id' => $promoCodeId,
            'total_after_discount' => $totalAfterDiscount,
        ]);

        return response()->json($cart);
    }
    public function getCartItems($id): JsonResponse
    {
        $cart = Cart::find($id);

        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }

        $items = json_decode($cart->items, true);
        $detailedItems = [];

        foreach ($items as $item) {
            $itemModel = Item::find($item['item_id']);
            if ($itemModel) {
                $detailedItems[] = [
                    'item_id' => $itemModel->id,
                    'quantity' => $item['quantity'],
                    'name' => $itemModel->name,
                    'price' => $itemModel->price
                ];
            }
        }

        return response()->json($detailedItems);
    }
    public function addItem(Request $request, $id): JsonResponse
    {
        $cart = Cart::findOrFail($id);
        $itemId = $request->input('item_id');
        $quantity = $request->input('quantity', 1);

        $item = Item::findOrFail($itemId);

        $items = json_decode($cart->items, true);
        $itemExists = false;

        foreach ($items as &$cartItem) {
            if ($cartItem['item_id'] == $itemId) {
                $cartItem['quantity'] += $quantity;
                $itemExists = true;
                break;
            }
        }

        if (!$itemExists) {
            $items[] = ['item_id' => $itemId, 'quantity' => $quantity];
        }

        $cart->items = json_encode($items);
        $cart->total += $item->price * $quantity;

        if ($cart->promo_code_id) {
            $promoCode = PromoCode::findOrFail($cart->promo_code_id);
            $discountPercentage = $promoCode->percentage;
            $discountAmount = ($cart->total * $discountPercentage) / 100;
            $cart->total_after_discount = $cart->total - $discountAmount;
        }

        $cart->save();

        return response()->json($cart);
    }
    public function removeItem($id, $itemId): JsonResponse
    {
        $cart = Cart::findOrFail($id);
        $itemIds = json_decode($cart->items, true);
        $itemIndex = -1;

        foreach ($itemIds as $index => $item) {
            if ($item['item_id'] == $itemId) {
                $itemIndex = $index;
                break;
            }
        }

        if ($itemIndex === -1) {
            return response()->json(['error' => 'Item not found in cart'], 404);
        }

        $item = Item::findOrFail($itemIds[$itemIndex]['item_id']);
        $cart->total -= $item->price * $itemIds[$itemIndex]['quantity'];

        if ($cart->promo_code_id) {
            $promoCode = PromoCode::findOrFail($cart->promo_code_id);
            $discountPercentage = $promoCode->percentage;
            $discountAmount = ($cart->total * $discountPercentage) / 100;
            $cart->total_after_discount = $cart->total - $discountAmount;
        }

        array_splice($itemIds, $itemIndex, 1);
        $cart->items = json_encode($itemIds);
        $cart->save();

        return response()->json($cart);
    }
}

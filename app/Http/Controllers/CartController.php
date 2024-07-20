<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCartRequest;
use App\Models\Cart;
use App\Models\Item;
use App\Models\PromoCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function store(StoreCartRequest $request): JsonResponse
    {
        $user = Auth::user();
        $items = $request->input('items', []);
        $total = 0;
        $cartItems = [];

        foreach ($items as $item) {
            $itemModel = Item::findOrFail($item['item_id']);
            $total += $itemModel->price * $item['quantity'];

            if (isset($cartItems[$item['item_id']])) {
                $cartItems[$item['item_id']]['quantity'] += $item['quantity'];
            } else {
                $cartItems[$item['item_id']] = [
                    'item_id' => $item['item_id'],
                    'quantity' => $item['quantity']
                ];
            }
        }

        $promoCodeId = $request->input('promo_code_id');
        $totalAfterDiscount = null;

        if ($promoCodeId) {
            $promoCode = Promocode::findOrFail($promoCodeId);
            $discountPercentage = $promoCode->percentage;
            $discountAmount = ($total * $discountPercentage) / 100;
            $totalAfterDiscount = $total - $discountAmount;
        }

        $cart = Cart::create([
            'user_id' => $user ? $user->id : null,
            'items' => json_encode(array_values($cartItems)),
            'total' => $total,
            'promo_code_id' => $promoCodeId,
            'total_after_discount' => $totalAfterDiscount,
        ]);

        return response()->json($cart);
    }

    public function getCartItems(Cart $cart): JsonResponse
    {
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
    public function addItem(Request $request, Cart $cart): JsonResponse
    {
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
    public function removeItem(Cart $cart, Item $item): JsonResponse
    {
        $items = json_decode($cart->items, true);
        $itemIndex = -1;

        foreach ($items as $index => $cartItem) {
            if ($cartItem['item_id'] == $item->id) {
                $itemIndex = $index;
                break;
            }
        }

        if ($itemIndex === -1) {
            return response()->json(['error' => 'Item not found in cart'], 404);
        }

        $cart->total -= $item->price * $items[$itemIndex]['quantity'];

        if ($cart->promo_code_id) {
            $promoCode = PromoCode::findOrFail($cart->promo_code_id);
            $discountPercentage = $promoCode->percentage;
            $discountAmount = ($cart->total * $discountPercentage) / 100;
            $cart->total_after_discount = $cart->total - $discountAmount;
        }

        array_splice($items, $itemIndex, 1);
        $cart->items = json_encode($items);
        $cart->save();

        return response()->json($cart);
    }

}

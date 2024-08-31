<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCartRequest;
use App\Models\Cart;
use App\Models\Item;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function store(StoreCartRequest $request): JsonResponse
    {
        $user = Auth::user();
        $items = $request->input('items', []);
        $total = $this->cartService->calculateTotal($items);

        $cart = Cart::create([
            'user_id' => $user ? $user->id : null,
            'items' => json_encode($items),
            'total' => $total,
        ]);

        $this->cartService->applyPromoCode($cart, $request->input('promo_code_id'));

        return response()->json($cart);
    }

    public function getCartItems(Cart $cart): JsonResponse
    {
        $detailedItems = $this->cartService->getDetailedItems($cart);
        return response()->json($detailedItems);
    }

    public function addItem(Request $request, Cart $cart): JsonResponse
    {
        $item = Item::findOrFail($request->input('item_id'));
        $quantity = $request->input('quantity', 1);

        $this->cartService->addItem($cart, $item, $quantity);

        return response()->json($cart);
    }

    public function removeItem(Cart $cart, Item $item): JsonResponse
    {
        try {
            $this->cartService->removeItem($cart, $item);
            return response()->json($cart);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
}

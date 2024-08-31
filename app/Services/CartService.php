<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Item;
use App\Models\Promocode;

class CartService
{
    public function calculateTotal(array $cartItems): float
    {
        $total = 0;
        foreach ($cartItems as $item) {
            $itemModel = Item::findOrFail($item['item_id']);
            $total += $itemModel->price * $item['quantity'];
        }
        return $total;
    }

    public function applyPromoCode(Cart $cart, $promoCodeId = null): void
    {
        if ($promoCodeId) {
            $promoCode = Promocode::findOrFail($promoCodeId);
            $discountAmount = ($cart->total * $promoCode->percentage) / 100;
            $cart->total_after_discount = $cart->total - $discountAmount;
        }
    }

    public function addItem(Cart $cart, Item $item, int $quantity): void
    {
        $items = json_decode($cart->items, true);
        $itemExists = false;

        foreach ($items as &$cartItem) {
            if ($cartItem['item_id'] == $item->id) {
                $cartItem['quantity'] += $quantity;
                $itemExists = true;
                break;
            }
        }

        if (!$itemExists) {
            $items[] = ['item_id' => $item->id, 'quantity' => $quantity];
        }

        $cart->items = json_encode($items);
        $cart->total += $item->price * $quantity;

        if ($cart->promo_code_id) {
            $this->applyPromoCode($cart, $cart->promo_code_id);
        }

        $cart->save();
    }

    public function removeItem(Cart $cart, Item $item): void
    {
        $items = json_decode($cart->items, true);
        $itemIndex = array_search($item->id, array_column($items, 'item_id'));

        if ($itemIndex === false) {
            throw new \Exception('Item not found in cart');
        }

        $cart->total -= $item->price * $items[$itemIndex]['quantity'];
        array_splice($items, $itemIndex, 1);

        $cart->items = json_encode($items);
        $this->applyPromoCode($cart, $cart->promo_code_id);

        $cart->save();
    }

    public function getDetailedItems(Cart $cart): array
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
                    'price' => $itemModel->price,
                ];
            }
        }

        return $detailedItems;
    }
}

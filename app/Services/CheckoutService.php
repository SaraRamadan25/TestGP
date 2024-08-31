<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Checkout;
use App\Models\PromoCode;
use App\Models\ShippingAddress;
use Illuminate\Support\Facades\Auth;

class CheckoutService
{
    /**
     * @throws \Exception
     */
    public function handleCheckout($request, Cart $cart): array
    {
        $user = Auth::user();
        if (!$user) {
            return ['error' => 'User not found'];
        }

        $shippingAddress = ShippingAddress::findOrFail($request->input('shipping_address_id'));
        $deliveryFee = $this->calculateDeliveryFee($shippingAddress);
        $totalAmount = $this->calculateTotalAmount($cart);
        $totalAfterDelivery = $totalAmount + $deliveryFee;

        $checkout = Checkout::create([
            'user_id' => $user->id,
            'shipping_address_id' => $shippingAddress->id,
            'payment_card_number' => $request->input('payment_card_number'),
            'delivery_method' => 'paypal',
            'total' => $totalAmount,
            'delivery_fee' => $deliveryFee,
            'total_after_delivery' => $totalAfterDelivery,
        ]);

        return $this->prepareResponse($user, $shippingAddress, $checkout);
    }

    private function calculateDeliveryFee(ShippingAddress $shippingAddress): float
    {
        $address = strtolower($shippingAddress->address);
        return match(true) {
            str_contains($address, 'dakehlia') => 30.00,
            str_contains($address, 'gharbia') => 50.00,
            str_contains($address, 'cairo') => 40.00,
            str_contains($address, 'alex') => 40.00,
            default => 60.00,
        };
    }

    private function calculateTotalAmount(Cart $cart): float
    {
        if ($cart->promo_code_id) {
            $promoCode = PromoCode::find($cart->promo_code_id);
            if ($promoCode && $promoCode->expires_at && $promoCode->expires_at->isPast()) {
                throw new \Exception('Promo code is expired');
            }
            return $cart->total_after_discount ?? $cart->total;
        }
        return $cart->total;
    }

    private function prepareResponse($user, $shippingAddress, $checkout): array
    {
        return [
            'user_name' => $user->name,
            'shipping_address' => $shippingAddress,
            'payment_card_number' => $checkout->payment_card_number,
            'delivery_method' => $checkout->delivery_method,
            'total_amount' => $checkout->total,
            'delivery_fee' => $checkout->delivery_fee,
            'total_after_delivery' => $checkout->total_after_delivery,
        ];
    }
}

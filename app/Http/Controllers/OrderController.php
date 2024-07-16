<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Checkout;
use App\Models\Order;
use App\Models\Promocode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function checkout(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'shipping_address' => 'required|string|max:255',
            'payment_card_number' => 'required|string|max:16',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        $cart = Cart::findOrFail($id);

        if (!$cart || !$user) {
            return response()->json(['error' => 'Cart or User not found'], 404);
        }

        $shippingAddress = $request->input('shipping_address');
        $deliveryMethod = 'paypal';
        $deliveryFee = $this->calculateDeliveryFee($shippingAddress);

        $totalAfterDelivery = $cart->total + $deliveryFee;

        if ($cart->promo_code_id) {
            $promoCode = PromoCode::findOrFail($cart->promo_code_id);
            $discountPercentage = $promoCode->percentage;
            $discountAmount = ($cart->total * $discountPercentage) / 100;
            $totalAfterDelivery -= $discountAmount;
        }

        $checkout = Checkout::create([
            'user_id' => $user->id,
            'shipping_address' => $shippingAddress,
            'payment_card_number' => $request->input('payment_card_number'),
            'delivery_method' => $deliveryMethod,
            'total' => $cart->total,
            'delivery_fee' => $deliveryFee,
            'total_after_delivery' => $totalAfterDelivery,
        ]);

        return response()->json([
            'user_name' => $user->name,
            'shipping_address' => $checkout->shipping_address,
            'payment_card_number' => $checkout->payment_card_number,
            'delivery_method' => $checkout->delivery_method,
            'total_amount' => $checkout->total,
            'delivery_fee' => $checkout->delivery_fee,
            'total_after_delivery' => $checkout->total_after_delivery
        ]);
    }

    private function calculateDeliveryFee($shippingAddress): float
    {
        $address = strtolower($shippingAddress);
        if (str_contains($address, 'dakehlia')) {
            return 30.00;
        } elseif (str_contains($address, 'gharbia')) {
            return 50.00;
        } elseif (str_contains($address, 'cairo')) {
            return 40.00;
        } elseif (str_contains($address, 'alex')) {
            return 40.00;
        }
        return 60.00;
    }}

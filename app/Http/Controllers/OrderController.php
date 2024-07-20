<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Http\Resources\Order\OrderCollection;
use App\Models\Cart;
use App\Models\Checkout;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Promocode;
use App\Models\ShippingAddress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function checkout(CheckoutRequest $request, Cart $cart): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $shippingAddress = ShippingAddress::findOrFail($request->input('shipping_address_id'));
        $deliveryMethod = 'paypal';
        $deliveryFee = $this->calculateDeliveryFee($shippingAddress);

        $totalAfterDelivery = $cart->total + $deliveryFee;

        if ($cart->promo_code_id) {
            $promoCode = Promocode::findOrFail($cart->promo_code_id);
            $discountPercentage = $promoCode->percentage;
            $discountAmount = ($cart->total * $discountPercentage) / 100;
            $totalAfterDelivery -= $discountAmount;
        }

        $checkout = Checkout::create([
            'user_id' => $user->id,
            'shipping_address_id' => $shippingAddress->id,
            'payment_card_number' => $request->input('payment_card_number'),
            'delivery_method' => $deliveryMethod,
            'total' => $cart->total,
            'delivery_fee' => $deliveryFee,
            'total_after_delivery' => $totalAfterDelivery,
        ]);

        return response()->json([
            'user_name' => $user->name,
            'shipping_address' => $shippingAddress,
            'payment_card_number' => $checkout->payment_card_number,
            'delivery_method' => $checkout->delivery_method,
            'total_amount' => $checkout->total,
            'delivery_fee' => $checkout->delivery_fee,
            'total_after_delivery' => $checkout->total_after_delivery,
        ]);
    }
    private function calculateDeliveryFee(ShippingAddress $shippingAddress): float
    {
        $totalFee = 0;
        $address = strtolower($shippingAddress->address);
        if (str_contains($address, 'dakehlia')) {
            $totalFee += 30.00;
        } elseif (str_contains($address, 'gharbia')) {
            $totalFee += 50.00;
        } elseif (str_contains($address, 'cairo')) {
            $totalFee += 40.00;
        } elseif (str_contains($address, 'alex')) {
            $totalFee += 40.00;
        } else {
            $totalFee += 60.00;
        }
        return $totalFee;
    }
    public function deliveredOrders(): OrderCollection
    {
        $user = Auth::user();

        $orders = Order::where('user_id', $user->id)
            ->where('status', 'delivered')
            ->get();

        return new OrderCollection($orders);
    }

    public function markAsDelivered($orderId): JsonResponse
    {
        $order = Order::findOrFail($orderId);
        $order->status = 'delivered';
        $order->save();

        Notification::create([
            'user_id' => $order->user_id,
            'title' => 'Your order #' . $order->id . ' has been delivered',
            'message' => 'Your order has been successfully delivered. Thank you for shopping with us!',
            'type' => 'order_delivered',
            'status' => 'new',
        ]);

        return response()->json(['message' => 'Order marked as delivered']);
    }

    public function markAsCancelled($orderId): JsonResponse
    {
        $order = Order::findOrFail($orderId);
        $order->status = 'cancelled';
        $order->save();

        // Create a notification
        Notification::create([
            'user_id' => $order->user_id,
            'title' => 'Your order #' . $order->id . ' has been cancelled',
            'message' => 'Your order has been cancelled. If you have any questions, please contact our support.',
            'type' => 'order_cancelled',
            'status' => 'new',
        ]);

        return response()->json(['message' => 'Order marked as cancelled']);
    }
}



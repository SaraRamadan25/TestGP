<?php

namespace App\Http\Controllers;

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
    public function checkout(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'shipping_address_id' => 'required|exists:shipping_addresses,id',
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

        $shippingAddress = ShippingAddress::findOrFail($request->input('shipping_address_id'));
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
            'total_after_delivery' => $checkout->total_after_delivery
        ]);
    }

    private function calculateDeliveryFee(ShippingAddress $shippingAddress): float
    {
        $totalFee = 0;
        // Assuming 'address' is a field on the ShippingAddress model. Adjust if necessary.
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

    public function allShippingAddresses(): JsonResponse
    {
        $user = Auth::user();

        $checkouts = Checkout::where('user_id', $user->id)
            ->select('shipping_address')
            ->distinct()
            ->get();

        $addresses = [];
        foreach ($checkouts as $checkout) {
            $decodedAddresses = json_decode($checkout->shipping_address, true);
            if (is_array($decodedAddresses)) {
                $addresses = array_merge($addresses, $decodedAddresses);
            }
        }

        $uniqueAddresses = array_unique($addresses);

        return response()->json(['shipping_addresses' => $uniqueAddresses]);
    }

    public function addShippingAddress(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'shipping_address' => 'required|array',
            'shipping_address.*' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();

        $checkout = Checkout::create([
            'user_id' => $user->id,
            'shipping_address' => json_encode($request->input('shipping_address')),
            'payment_card_number' => '0000000000000000',
            'delivery_method' => 'paypal',
            'total' => 0,
            'delivery_fee' => 0,
            'total_after_delivery' => 0,
        ]);

        return response()->json(['shipping_address' => $checkout->shipping_address]);
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



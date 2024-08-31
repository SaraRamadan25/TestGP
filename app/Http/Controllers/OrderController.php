<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Services\CheckoutService;
use App\Models\Cart;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    protected CheckoutService $checkoutService;

    public function __construct(CheckoutService $checkoutService)
    {
        $this->checkoutService = $checkoutService;
    }

    public function checkout(CheckoutRequest $request, Cart $cart): JsonResponse
    {
        try {
            $response = $this->checkoutService->handleCheckout($request, $cart);
            if (isset($response['error'])) {
                return response()->json(['error' => $response['error']], 404);
            }
            return response()->json($response, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}

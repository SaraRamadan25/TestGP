<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentMethodRequest;
use App\Models\PaymentMethod;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentMethodController extends Controller
{
    public function store(StorePaymentMethodRequest $request): JsonResponse
    {
    $paymentMethod = new PaymentMethod();

    $paymentMethod->fill($request->validated());

    $paymentMethod->save();

    return response()->json(['message' => 'Payment method created successfully', 'payment_method' => $paymentMethod], 201);
}
}

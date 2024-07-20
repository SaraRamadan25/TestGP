<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShippingAddressRequest;
use App\Http\Requests\UpdateShippingAddressRequest;
use App\Models\ShippingAddress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShippingAddressController extends Controller
{
    public function index(): JsonResponse
    {
        $shippingAddresses = Auth::user()->shippingAddresses;
        return response()->json($shippingAddresses);
    }

    public function store(StoreShippingAddressRequest $request): JsonResponse
    {
        $shippingAddress = ShippingAddress::create($request->validated() + ['user_id' => Auth::id()]);
        return response()->json($shippingAddress, 201);
    }
    public function show(ShippingAddress $shippingAddress): JsonResponse
    {
        return response()->json($shippingAddress);
    }

    public function update(UpdateShippingAddressRequest $request, ShippingAddress $shippingAddress): JsonResponse
    {
        $shippingAddress->update($request->validated());

        return response()->json($shippingAddress);
    }
}

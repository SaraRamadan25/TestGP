<?php

namespace App\Http\Controllers;

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

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'country_id' => 'required|exists:countries,id',
            'city_id' => 'required|exists:cities,id',
            'district_id' => 'nullable|exists:districts,id',
        ]);

        $shippingAddress = ShippingAddress::create([
            'user_id' => Auth::id(),
            'full_name' => $request->full_name,
            'address' => $request->address,
            'postal_code' => $request->postal_code,
            'country_id' => $request->country_id,
            'city_id' => $request->city_id,
            'district_id' => $request->district_id,
        ]);

        return response()->json($shippingAddress, 201);
    }
    public function show($id): JsonResponse
    {
        $shippingAddress = Auth::user()->shippingAddresses()->findOrFail($id);
        return response()->json($shippingAddress);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'country_id' => 'required|exists:countries,id',
            'city_id' => 'required|exists:cities,id',
            'district_id' => 'nullable|exists:districts,id',
        ]);

        $shippingAddress = Auth::user()->shippingAddresses()->findOrFail($id);

        $shippingAddress->update($request->all());

        return response()->json($shippingAddress);
    }

}

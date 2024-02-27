<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHealthRequest;
use App\Models\Health;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class HealthController extends Controller
{
    public function store(StoreHealthRequest $request) :JsonResponse
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'You must be logged in to perform this action'], 401);
        }

        $validatedData = $request->validated();

        $validatedData['user_id'] = Auth::id();

        Health::create($validatedData);

        return response()->json(['message' => 'We have saved your health Info !'], 200);
    }
    public function destroy(Health $health): Response
    {
        $health->delete();
        return response()->noContent();
    }
}

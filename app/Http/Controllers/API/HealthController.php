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
    public function store(StoreHealthRequest $request): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validatedData = $request->validated();
        $validatedData['user_id'] = $user->id;

        Health::create($validatedData);

        return response()->json(['message' => 'We have saved your health info!'], 200);
    }
}


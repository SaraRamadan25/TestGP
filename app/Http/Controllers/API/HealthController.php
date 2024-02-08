<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\HealthDataRequest;
use App\Models\Health;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class HealthController extends Controller
{
    public function store(HealthDataRequest $request) :JsonResponse
    {
        $validatedData = $request->validated();

        $validatedData['user_id'] = Auth::id();

        Health::create($validatedData);

        return response()->json(['message' => 'We have saved your health Info !'], 200);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\HealthDataRequest;
use App\Models\Health;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

<?php

namespace App\Http\Controllers\API;

use App\Events\SensorDataReceived;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SensorController extends Controller
{
    public function getData(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        event(new SensorDataReceived($data));

        return response()->json(['message' => 'Data received successfully']);
    }
}

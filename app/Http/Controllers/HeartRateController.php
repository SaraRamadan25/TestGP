<?php

namespace App\Http\Controllers;

use App\Events\HeartRateUpdated;
use Illuminate\Http\JsonResponse;

class HeartRateController extends Controller
{
    public function getHeartRate(): JsonResponse
    {
        $heartRate = rand(60, 100);

        event(new HeartRateUpdated($heartRate));
        return response()->json(['heart_rate' => $heartRate]);
    }
}

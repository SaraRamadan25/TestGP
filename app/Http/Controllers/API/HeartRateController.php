<?php

namespace App\Http\Controllers\API;

use App\Events\HeartRateUpdated;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class HeartRateController extends Controller
{
    public function getHeartRate(): JsonResponse
    {
        $heartRate = rand(60, 200);

        event(new HeartRateUpdated($heartRate));
        return response()->json(['heart_rate' => $heartRate]);
    }
    public function fetchDataFromSensor(): JsonResponse
    {
        $sensorUrl = 'http://SENSOR_IP/SENSOR_API_ENDPOINT';

        $sensorData = Http::get($sensorUrl)->json();

        broadcast(new \App\Events\HeartRateUpdated($sensorData))->toOthers();
        return response()->json(['message' => 'Data sent to Pusher']);
    }
}

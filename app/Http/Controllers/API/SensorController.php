<?php

namespace App\Http\Controllers\API;

use App\Events\SensorDataReceived;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SensorController extends Controller
{
    public function getData(Request $request): JsonResponse
    {
        $inputData = json_decode($request->getContent(), true);

        $gpsData = $inputData['gpsData'];
        $healthData = $inputData['healthData'];
        $relayStatus = $inputData['relayStatus'];

        $latitude = $gpsData['lat'];
        $longitude = $gpsData['lng'];
        $heartRate = $healthData['heartRate'];
        $spo2 = $healthData['spo2'];

        Log::info('Received sensor data', [
            'heartRate' => $heartRate,
            'spo2' => $spo2,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'relayStatus' => $relayStatus,
        ]);

        broadcast(new SensorDataReceived($heartRate, $spo2, $latitude, $longitude,$relayStatus))->toOthers();

        return response()->json(['message' => 'Data received successfully']);
    }
}

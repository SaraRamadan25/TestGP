<?php

namespace App\Http\Controllers\API;

use App\Events\SensorDataReceived;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SensorController extends Controller
{

    public function getData(Request $request): JsonResponse
    {
        $inputData = json_decode($request->getContent(), true);

        if (isset($inputData['gpsData'])) {
            $this->handleGpsData(json_decode($inputData['gpsData'], true));
        }

        if (isset($inputData['healthData'])) {
            $this->handleHealthData(json_decode($inputData['healthData'], true));
        }

        if (isset($inputData['relayStatus'])) {
            $this->handleRelayStatus($inputData['relayStatus']);
        }

        return response()->json(['message' => 'Data received successfully']);
    }

    private function handleGpsData(array $gpsData)
    {
        $validatedData = Validator::make($gpsData, [
            'lat' => 'sometimes|numeric',
            'lng' => 'sometimes|numeric',
        ])->validate();

        $latitude = $validatedData['lat'] ?? null;
        $longitude = $validatedData['lng'] ?? null;

        Log::info('Received GPS data', [
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);

        broadcast(new SensorDataReceived(null, null, $latitude, $longitude, null))->toOthers();
    }

    private function handleHealthData(array $healthData)
    {
        $validatedData = Validator::make($healthData, [
            'heartRate' => 'sometimes|numeric',
            'spo2' => 'sometimes|numeric',
        ])->validate();

        $heartRate = $validatedData['heartRate'] ?? null;
        $spo2 = $validatedData['spo2'] ?? null;

        Log::info('Received health data', [
            'heartRate' => $heartRate,
            'spo2' => $spo2,
        ]);

        broadcast(new SensorDataReceived($heartRate, $spo2, null, null, null))->toOthers();
    }

    private function handleRelayStatus($relayStatus)
    {
        $validatedData = Validator::make(['relayStatus' => $relayStatus], [
            'relayStatus' => 'sometimes|boolean',
        ])->validate();

        $relayStatus = $validatedData['relayStatus'] ?? null;

        Log::info('Received relay status', [
            'relayStatus' => $relayStatus,
        ]);

        broadcast(new SensorDataReceived(null, null, null, null, $relayStatus))->toOthers();
    }
}

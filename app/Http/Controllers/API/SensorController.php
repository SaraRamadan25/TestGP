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

        $gpsData = json_decode($inputData['gpsData'], true);
        $healthData = json_decode($inputData['healthData'], true);
        $relayStatus = $inputData['relayStatus'];

        try {
            $validatedData = Validator::make($gpsData + $healthData + ['relayStatus' => $relayStatus], [
                'lat' => 'sometimes|numeric',
                'lng' => 'sometimes|numeric',
                'heartRate' => 'sometimes|numeric',
                'spo2' => 'sometimes|numeric',
                'relayStatus' => 'sometimes|boolean',
            ])->validate();

            $latitude = $validatedData['lat'] ?? null;
            $longitude = $validatedData['lng'] ?? null;
            $heartRate = $validatedData['heartRate'] ?? null;
            $spo2 = $validatedData['spo2'] ?? null;
            $relayStatus = $validatedData['relayStatus'] ?? null;

            Log::info('Received sensor data', [
                'heartRate' => $heartRate,
                'spo2' => $spo2,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'relayStatus' => $relayStatus,
            ]);

            broadcast(new SensorDataReceived($heartRate, $spo2, $latitude, $longitude, $relayStatus))->toOthers();

            return response()->json(['message' => 'Data received successfully']);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Invalid data received', 'errors' => $e->errors()], 422);
        }
    }
    public function simulateSensorData(): JsonResponse
    {
        $mockData = [
            'gpsData' => json_encode([
                'lat' => 51.509865,
                'lng' => -0.118092
            ]),
            'healthData' => json_encode([
                'heartRate' => 72,
                'spo2' => 98
            ]),
            'relayStatus' => true
        ];

        $mockRequest = new Request([], [], [], [], [], [], json_encode($mockData));

        return $this->getData($mockRequest);
    }

}


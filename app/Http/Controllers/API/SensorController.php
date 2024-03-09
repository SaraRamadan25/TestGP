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


        try {
            $validatedData = Validator::make($inputData, [
                'gpsData.lat' => 'sometimes|numeric',
                'gpsData.lng' => 'sometimes|numeric',
                'healthData.heartRate' => 'sometimes|numeric',
                'healthData.spo2' => 'sometimes|numeric',
                'relayStatus' => 'sometimes|boolean',
            ])->validate();

            $gpsData = $validatedData['gpsData'] ?? [];
            $healthData = $validatedData['healthData'] ?? [];
            $relayStatus = $validatedData['relayStatus'] ?? null;

            $latitude = $gpsData['lat'] ?? null;
            $longitude = $gpsData['lng'] ?? null;
            $heartRate = $healthData['heartRate'] ?? null;
            $spo2 = $healthData['spo2'] ?? null;

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
    }}

<?php

namespace App\Http\Controllers;

use App\Events\SensorDataReceived;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SensorController extends Controller
{
    public function getData(Request $request): JsonResponse
    {
        Log::info('Received sensor data', $request->all());

        // Get the HeartRate and Spo2 query parameters
        $heartRate = $request->query('HeartRate');
        $spo2 = $request->query('Spo2');

        // Optionally, you may validate or sanitize the received data here

        // Broadcast the data to Pusher
        event(new SensorDataReceived($heartRate, $spo2));

        // Return the data as a JSON response
        return response()->json([
            'HeartRate' => $heartRate,
            'Spo2' => $spo2,
        ]);

    }

        public function storeData(Request $request)
    {
        $heartRate = $request->input('HeartRate');
        $spo2 = $request->input('Spo2');

        event(new SensorDataReceived($heartRate, $spo2));

        return response()->json(['message' => 'Data received successfully']);
    }
}

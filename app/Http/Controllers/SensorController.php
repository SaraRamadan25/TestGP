<?php

namespace App\Http\Controllers;

use App\Events\SensorDataReceived;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SensorController extends Controller
{
    public function getData(Request $request)
    {
        // Retrieve data from the request
        $heartRate = $request->query('HeartRate');
        $spo2 = $request->query('Spo2');

        // Log the received data (optional)
        Log::info('Received sensor data', [
            'heartRate' => $heartRate,
            'spo2' => $spo2,
        ]);

        // Broadcast the received data to Pusher
        broadcast(new SensorDataReceived($heartRate, $spo2))->toOthers();

        // Optionally, return a response
        return response()->json(['message' => 'Data received successfully']);
    }}

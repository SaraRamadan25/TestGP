<?php

namespace App\Http\Controllers\API;

use App\Events\SensorDataReceived;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SensorController extends Controller
{
    public function getData(Request $request)
    {
        $heartRate = $request->input('HeartRate');
        $spo2 = $request->input('Spo2');

        Log::info('Received sensor data', [
            'heartRate' => $heartRate,
            'spo2' => $spo2,
        ]);

        broadcast(new SensorDataReceived($heartRate, $spo2))->toOthers();

        return response()->json(['message' => 'Data received successfully']);
    }
    public function receive(Request $request)
    {
        $heartRate = $request->query('HeartRate');
        $spo2 = $request->query('Spo2');

        Log::info('Received sensor data', [
            'heartRate' => $heartRate,
            'spo2' => $spo2,
        ]);

        broadcast(new SensorDataReceived($heartRate, $spo2))->toOthers();

        return response()->json(['message' => 'Data received successfully']);
    }
    public function sensorData(Request $request)
    {
        $heartRate = $request->input('HeartRate');
        $spo2 = $request->input('Spo2');

        Log::info('Received sensor data', [
            'heartRate' => $heartRate,
            'spo2' => $spo2,
        ]);

        broadcast(new SensorDataReceived($heartRate, $spo2))->toOthers();

        return response()->json(['message' => 'Data received successfully']);
    }
}

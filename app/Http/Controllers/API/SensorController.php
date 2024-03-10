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
    public function getData(Request $request)
    {
        $data = $request->json()->all();

        $dataType = $this->getDataType($data);

        broadcast(new SensorDataReceived($dataType, $data));

        return response()->json(['Data Received successfully' => true]);
    }

    private function getDataType($data)
    {
        if (isset($data['lat']) && isset($data['lng'])) {
            return 'gpsData';
        } elseif (isset($data['heartRate']) && isset($data['spo2'])) {
            return 'healthData';
        } elseif (isset($data['relayStatus'])) {
            return 'relayStatus';
        } else {
            return 'unknown';
        }

    }
}

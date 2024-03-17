<?php

namespace App\Listeners;

use App\Events\SensorDataReceived;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

class SensorDataListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SensorDataReceived $event)
    {
        $heartRate = $event->data['heartRate'] ?? null;
        $spo2 = $event->data['spo2'] ?? null;
        $longitude = $event->data['lng'] ?? null;
        $latitude = $event->data['lat'] ?? null;
        $relayStatus = $event->data['relayStatus'] ?? null;

        Log::info('Received sensor data', [
            'heartRate' => $heartRate,
            'spo2' => $spo2,
            'longitude' => $longitude,
            'latitude' => $latitude,
            'relayStatus' => $relayStatus,
        ]);
    }
}

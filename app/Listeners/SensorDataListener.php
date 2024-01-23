<?php

namespace App\Listeners;

use App\Events\SensorDataReceived;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Broadcast;

class SensorDataListener
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
        // Broadcast the data to the "sensor-data" channel
        Broadcast::channel('sensor-data', function ($user) use ($event) {
            return [
                'heartRate' => $event->heartRate,
                'spo2' => $event->spo2,
            ];
        });
    }
}

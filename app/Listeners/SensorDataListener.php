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
        // Log the received data (optional)
        Log::info('Received sensor data', [
            'heartRate' => $event->heartRate,
            'spo2' => $event->spo2,
        ]);


    }
}

<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SensorDataReceived implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $heartRate;
    public $spo2;
    public $latitude;
    public $longitude;
    public $relayStatus;

    public function __construct($heartRate, $spo2, $latitude, $longitude, $relayStatus)
    {
        $this->heartRate = $heartRate;
        $this->spo2 = $spo2;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->relayStatus = $relayStatus;
    }
    public function broadcastOn(): Channel
    {
        return new Channel('sensor-data');
    }
    public function broadcastAs(): string
    {
        return 'sensor-data';
    }
}

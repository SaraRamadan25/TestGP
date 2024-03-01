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
    public int $heartRate;
    public float $spo2;
    public float $lat;
    public float $lng;

    public function __construct($heartRate, $spo2, $latitude, $longitude)
    {
        $this->heartRate = $heartRate;
        $this->spo2 = $spo2;
        $this->lat= $latitude;
        $this->lng= $longitude;
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

<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class HeartRateUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $heartRate;

    public function __construct($heartRate)
    {
        $this->heartRate = $heartRate;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('heart-rate');
    }

    public function broadcastAs(): string
    {
        return 'heart-rate';
    }
}

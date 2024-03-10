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
    public $dataType;
    public $data;

    public function __construct($dataType, $data)
    {
        $this->dataType = $dataType;
        $this->data = $data;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('data-channel');
    }

    public function broadcastAs(): string
    {
        return 'data.'.$this->dataType;
    }
}

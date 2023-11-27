<?php
namespace App\Events;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class LocationUpdated implements ShouldBroadcast
{
    use SerializesModels;

    public $latitude;
    public $longitude;
    public $country;
    public $continent;

    public function __construct($latitude, $longitude, $country, $continent)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->country = $country;
        $this->continent = $continent;
    }

    public function broadcastOn()
    {
        return ['location-channel'];
    }
}

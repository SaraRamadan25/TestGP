<?php

namespace App\Listeners;

use App\Events\HeartRateUpdated;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Pusher\ApiErrorException;
use Pusher\Pusher;
use Pusher\PusherException;

class HeartRateListener implements ShouldQueue
{
    /**
     * @throws PusherException
     * @throws GuzzleException
     * @throws ApiErrorException
     */
    public function handle(HeartRateUpdated $event)
    {
        if ($event->heartRate > 100) {
            $pusher = new Pusher(config('broadcasting.connections.pusher.key'), config('broadcasting.connections.pusher.secret'), config('broadcasting.connections.pusher.app_id'), [
                'cluster' => config('broadcasting.connections.pusher.options.cluster'),
                'useTLS' => true,
            ]);

            $channel = 'heart-rate-channel';
            $eventName = 'high-heart-rate';

            $data = [
                'message' => 'High heart rate detected. Please seek medical attention.',
                'heart_rate' => $event->heartRate,
            ];

            $pusher->trigger($channel, $eventName, $data);
        }
    }
}

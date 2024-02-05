<?php

namespace App\Jobs;

use App\Notifications\HighHeartRateNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Pusher\Pusher;

class HeartRateGenerator implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private const MAX_REQUESTS = 60; // 60 seconds in a minute

    public function handle(): void
    {
        $heartRates = [];

        // Generate heart rate values every second
        for ($i = 1; $i <= self::MAX_REQUESTS; $i++) {
            $heartRate = rand(80, 250);

            // Log heart rate value
            Log::info("Heart rate: $heartRate");

            // Add heart rate to array for average calculation
            $heartRates[] = $heartRate;

            // Broadcast the heart rate to Pusher
            $this->broadcastHeartRate($heartRate);

            sleep(1);
        }

        // Calculate average heart rate
        $averageHeartRate = array_sum($heartRates) / count($heartRates);

        // Log average heart rate
        Log::info("Average heart rate: $averageHeartRate");

        // Broadcast danger message if average is over 150
        if ($averageHeartRate > 150) {
            $this->broadcastDangerMessage($averageHeartRate);
            $this->sendDangerNotification();
        }

    }

    private function broadcastHeartRate(int $heartRate): void
    {
    }

    private function broadcastDangerMessage(float $averageHeartRate): void
    {
        // Broadcast danger message logic
        $pusher = new Pusher(
            config('broadcasting.connections.pusher.key'),
            config('broadcasting.connections.pusher.secret'),
            config('broadcasting.connections.pusher.app_id'),
            [
                'cluster' => config('broadcasting.connections.pusher.options.cluster'),
                'useTLS' => true,
            ]);

        $channel = 'danger-channel';
        $eventName = 'danger-message';

        $data = [
            'message' => 'Danger: High average heart rate detected. Please seek medical attention.',
            'average_heart_rate' => $averageHeartRate,
        ];

        // Trigger the danger event on Pusher
        $pusher->trigger($channel, $eventName, $data);
    }

    private function sendDangerNotification(): void
    {
        Notification::route('mail', 'toka@gmail.com')->notify(new HighHeartRateNotification());
    }
}

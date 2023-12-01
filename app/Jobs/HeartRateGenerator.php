<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Pusher\ApiErrorException;
use Pusher\Pusher;
use Pusher\PusherException;

class HeartRateGenerator implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private const MAX_REQUESTS = 60; // 60 seconds in a minute
    private const DANGER_THRESHOLD = 170; // Updated danger threshold

    /**
     * Execute the job.
     *
     * @return void
     * @throws PusherException
     * @throws ApiErrorException
     */
    public function handle(): void
    {
        $heartRates = [];

        // Generate heart rate values every second
        for ($i = 1; $i <= self::MAX_REQUESTS; $i++) {
            $heartRate = rand(60, 250);

            // Broadcast the heart rate to Pusher
            $this->broadcastHeartRate($heartRate);

            // Add heart rate to array for average calculation
            $heartRates[] = $heartRate;
        }

        // Calculate average heart rate
        $averageHeartRate = array_sum($heartRates) / count($heartRates);

        // Broadcast danger message if average is over the threshold and any individual heart rate is above 170
        if ($averageHeartRate > self::DANGER_THRESHOLD && max($heartRates) > 170) {
            $this->broadcastDangerMessage($averageHeartRate);
        } else {
            // Broadcast normal heart rate message
            $this->broadcastNormalHeartRate($averageHeartRate);
        }
    }

    private function broadcastHeartRate(int $heartRate): void
    {
        // Your existing broadcastHeartRate logic

        try {
            $pusher = new Pusher(
                config('broadcasting.connections.pusher.key'),
                config('broadcasting.connections.pusher.secret'),
                config('broadcasting.connections.pusher.app_id'),
                [
                    'cluster' => config('broadcasting.connections.pusher.options.cluster'),
                    'useTLS' => true,
                ]);

            $channel = 'heart-rate-channel';
            $eventName = 'heart-rate-updated';

            $data = [
                'heart_rate' => $heartRate,
            ];

            // Trigger the event on Pusher
            $pusher->trigger($channel, $eventName, $data);

            if ($heartRate > 100) {
                $warningChannel = 'warning-channel';
                $warningEventName = 'high-heart-rate-warning';

                $warningData = [
                    'message' => 'High heart rate detected. Please seek medical attention.',
                    'heart_rate' => $heartRate,
                ];

                // Trigger the warning event on Pusher
                $pusher->trigger($warningChannel, $warningEventName, $warningData);
            } else {
                // Broadcast normal heart rate message
                $this->broadcastNormalHeartRateMessage($heartRate);
            }
        } catch (PusherException $e) {
            // Handle Pusher exception, you may log it or perform other actions if needed
        }
    }

    /**
     * @throws PusherException
     * @throws ApiErrorException
     */
    private function broadcastDangerMessage(float $averageHeartRate): void
    {
        try {
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
        } catch (PusherException $e) {
            // Handle Pusher exception, you may log it or perform other actions if needed
        }
    }

    /**
     * @throws PusherException
     * @throws ApiErrorException
     */
    private function broadcastNormalHeartRate(float $averageHeartRate): void
    {
        try {
            $pusher = new Pusher(
                config('broadcasting.connections.pusher.key'),
                config('broadcasting.connections.pusher.secret'),
                config('broadcasting.connections.pusher.app_id'),
                [
                    'cluster' => config('broadcasting.connections.pusher.options.cluster'),
                    'useTLS' => true,
                ]);

            $channel = 'normal-channel';
            $eventName = 'normal-message';

            $data = [
                'message' => 'Normal: Average heart rate is within the normal range.',
                'average_heart_rate' => $averageHeartRate,
            ];

            // Trigger the normal event on Pusher
            $pusher->trigger($channel, $eventName, $data);
        } catch (PusherException $e) {
            // Handle Pusher exception, you may log it or perform other actions if needed
        }
    }

    /**
     * @throws PusherException
     * @throws ApiErrorException
     */
    private function broadcastNormalHeartRateMessage(float $heartRate): void
    {
        try {
            $pusher = new Pusher(
                config('broadcasting.connections.pusher.key'),
                config('broadcasting.connections.pusher.secret'),
                config('broadcasting.connections.pusher.app_id'),
                [
                    'cluster' => config('broadcasting.connections.pusher.options.cluster'),
                    'useTLS' => true,
                ]);

            $channel = 'normal-channel';
            $eventName = 'normal-message';

            $data = [
                'message' => 'Normal: Heart rate is within the normal range, Everything is fine.',
                'heart_rate' => $heartRate,
            ];

            // Trigger the normal event on Pusher
            $pusher->trigger($channel, $eventName, $data);
        } catch (PusherException $e) {
            // Handle Pusher exception, you may log it or perform other actions if needed
        }
    }
}

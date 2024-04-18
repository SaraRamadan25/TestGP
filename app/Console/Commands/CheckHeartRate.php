<?php

// app/Console/Commands/CheckHeartRate.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Exception\FirebaseException;

class CheckHeartRate extends Command
{
    protected $signature = 'heart-rate:check';
    protected $description = 'Check heart rate and send notifications if condition is met';

    public function handle()
    {
        try {
            $factory = app('firebase');
            $database = $factory->createDatabase();
            $sensorsData = $database->getReference('sensorsData')->getSnapshot()->getValue();

            if ($sensorsData['healthData']['heartRate'] > 140) {
                $deviceToken = env('DEVICE_TOKEN');
                $title = 'Heart Rate Alert';
                $body = 'Your heart rate is too high!';

                $notification = Notification::fromArray([
                    'title' => $title,
                    'body' => $body
                ]);

                $message = CloudMessage::withTarget('token', $deviceToken)
                    ->withNotification($notification);

                $messaging = $factory->createMessaging();
                $messaging->send($message);

                $this->info('Notification sent successfully');
            } else {
                // If heartRate is not greater than 140, log a message
                $this->info('Heart rate is not greater than 140. No notification sent.');
            }
        } catch (FirebaseException $e) {
            $this->error('Firebase Exception: ' . $e->getMessage());
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }
}


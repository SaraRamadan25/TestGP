<?php


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

    public function handle(): void
    {
        try {
            $factory = app('firebase');
            $database = $factory->createDatabase();
            $sensorsData = $database->getReference('sensorsData')->getSnapshot()->getValue();

            if ($sensorsData['healthData']['heartRate'] > 125) {
                $deviceToken = env('DEVICE_TOKEN');
                $title = 'Alert!';
                $body = "Your child's vital signs are too high! , please check him/her now!";

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
                $this->info('Vital signs are okay. No notification sent.');
            }
        } catch (FirebaseException $e) {
            $this->error('Firebase Exception: ' . $e->getMessage());
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }
}


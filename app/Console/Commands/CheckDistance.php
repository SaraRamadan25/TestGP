<?php


namespace App\Console\Commands;

use Illuminate\Console\Command;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Exception\FirebaseException;

class CheckDistance extends Command
{
    protected $signature = 'distance:check';
    protected $description = 'Check distance and send notification if condition is met';

    public function handle(): void
    {
        try {
            $factory = app('firebase');
            $database = $factory->createDatabase();
            $sensorsData = $database->getReference('sensorsData')->getSnapshot()->getValue();

            if ($sensorsData['distance'] > 10) {
                $deviceToken = env('DEVICE_TOKEN');
                $title = 'Alert!';
                $body = 'Your child is beyond the 10m limit , please check him/her now!';

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
                $this->info('Distance is not greater than 10 meters. No notification sent.');
            }
        } catch (FirebaseException $e) {
            $this->error('Firebase Exception: ' . $e->getMessage());
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }
}


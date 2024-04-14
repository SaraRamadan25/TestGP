<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FirebaseController extends Controller
{
    public function sendNotifications()
    {
        // Initialize Firebase Admin SDK
        $factory = app('firebase');

        // Retrieve sensor data
        $sensorData = $this->retrieveSensorData();

        // Check if the heart rate reading is above 140
        if ($sensorData['healthData']['heartRate'] > 140) {
            // Send FCM notification
            $messaging = $factory->createMessaging();
            $message = CloudMessage::withTarget('topic', 'health_alerts') // replace 'health_alerts' with your actual topic
            ->withNotification(Notification::create('High Heart Rate Alert', 'Heart rate is above 140'))
                ->withData(['heartRate' => $sensorData['healthData']['heartRate']]);

            $messaging->send($message);
            return response()->json(['message' => 'Notifications sent successfully']);
        }

        // You can add more conditions and notifications based on other sensor readings
        return response()->json(['message' => 'No notifications sent']);
    }

    // You need to implement the retrieveSensorData method
    private function retrieveSensorData()
    {
        // Initialize Firebase Admin SDK
        $factory = app('firebase');

        // Get a reference to the database
        $database = $factory->createDatabase();

        // Get a reference to the 'sensors' node and retrieve the latest data
        return $database->getReference('sensorsData')->getSnapshot()->getValue();
    }
}

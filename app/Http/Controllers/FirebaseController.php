<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FirebaseController extends Controller
{

    public function sendNotification(Request $request)
    {
        $factory = app('firebase');

        $deviceToken = $request->input('device_token');
        $title = $request->input('title');
        $body = $request->input('body');

        $notification = Notification::fromArray([
            'title' => $title,
            'body' => $body
        ]);

        $message = CloudMessage::withTarget('token', $deviceToken)
            ->withNotification($notification);

        $messaging = $factory->createMessaging();
        $messaging->send($message);

        return response()->json(['message' => 'Notification sent successfully']);
    }
}

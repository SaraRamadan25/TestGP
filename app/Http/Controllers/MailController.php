<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function sendTestEmail(): string
    {
        $to_name = 'Test User';
        $to_email = 'test@example.com';
        $data = array('name' => "Test", "body" => "Test email");

        Mail::send('emails.test', $data, function($message) use ($to_name, $to_email) {
            $message->to($to_email, $to_name)
                ->subject('Laravel Test Mail');
            $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
        });

        return 'Email sent successfully';
    }
}

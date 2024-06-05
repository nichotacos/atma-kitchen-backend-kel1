<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

class FirebaseService
{
    protected $messaging;

    public function __construct()
    {
        $factory = (new Factory)->withServiceAccount(config('services.firebase.credentials_file'));
        $this->messaging = $factory->createMessaging();
    }

    public function sendNotification($title, $body, $token)
    {
        try {
            $message = CloudMessage::withTarget('token', $token)
                ->withNotification(['title' => $title, 'body' => $body]);

            $this->messaging->send($message);
            \Log::info('Notification sent successfully with title: ' . $title . ' and body: ' . $body);
        } catch (\Exception $e) {
            \Log::error('Failed to send notification: ' . $e->getMessage());
        }
    }
}

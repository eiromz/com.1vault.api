<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\MessagingException;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class Firebase
{
    private object $factory;

    private string $storage;

    public object $messaging;

    public string $token;

    private string $filePath = 'vaultfirebaseadminsdk4951e6a7e1.json';

    public function __construct($token)
    {
        $this->storage = Storage::disk('local')->get($this->filePath);
        $this->factory = (new Factory)->withServiceAccount($this->storage);
        $this->messaging = $this->factory->createMessaging();
        $this->token = $token;
    }

    public function sendMessageWithToken(array $notification, array $data): void
    {
        try {
            $message = CloudMessage::withTarget('token', $this->token)
                ->withNotification($this->notification($notification))
                ->withData($data);

            $this->messaging->send($message);
        } catch (MessagingException|FirebaseException $e) {
            logExceptionErrorMessage('FirebaseService', $e);
        }
    }

    public function notification($notification)
    {
        return Notification::fromArray($notification);
    }
}

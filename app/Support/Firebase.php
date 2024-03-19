<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\MessagingException;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use ExponentPhpSDK\Expo;

class Firebase
{
    private object $factory;

    private string $storage;

    public object $messaging;

    public $token;

    private string $filePath;

    private string $channelName = 'default';

    public function __construct($token)
    {
        $this->filePath = config('app.firebase_service_file');
        $this->storage = Storage::disk('do')->get($this->filePath);
        $this->factory = (new Factory)->withServiceAccount($this->storage);
        $this->messaging = $this->factory->createMessaging();
        $this->token = $token;
    }

    public function sendMessageWithToken(array $notification, array $data): void
    {
        try {
            if (is_null($this->token)) {
                return;
            }

            $expo = Expo::normalSetup();

            $expo->subscribe($this->channelName, $this->token);

            $expo->notify([$this->channelName], $notification);

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

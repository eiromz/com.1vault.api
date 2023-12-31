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
    public $messaging;
    public $token;
    private string $filePath = 'uploads/testwhispa2firebase11d9770a31.json';
    public function __construct($token)
    {
        $this->storage      = Storage::disk('local')->get($this->filePath);
        $this->factory      = (new Factory)->withServiceAccount($this->storage);
        $this->messaging    = $this->factory->createMessaging();
        $this->token        = $token;
    }

    /**
     * @param $token
     * @param array $notification
     * @param array $data
     * @return void
     * @throws FirebaseException
     * @throws MessagingException
     */
    public function sendMessageWithToken(array $notification, array $data)
    {
        if(!is_null($this->token)) {
            $message = CloudMessage::withTarget('token',$this->token)
                ->withNotification($this->notification($notification))
                ->withData($data);

            $this->messaging->send($message);
        }
    }

    public function notification($notification)
    {
        return Notification::fromArray($notification);
    }

}

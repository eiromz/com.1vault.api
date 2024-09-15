<?php

namespace App\Support;

use ExpoSDK\Exceptions\ExpoException;
use ExpoSDK\Exceptions\ExpoMessageException;
use ExpoSDK\Exceptions\InvalidTokensException;
use ExpoSDK\Expo;
use ExpoSDK\ExpoMessage;

class Firebase
{
    public object $message;

    public $recipients = [];

    private string $channelName = 'default';

    public function __construct($token)
    {
        $this->recipients = [$token];
    }

    /**
     * @throws ExpoException
     * @throws InvalidTokensException
     * @throws ExpoMessageException
     */
    public function sendMessageWithToken(array $notification, array $data): void
    {
        $this->message = (new ExpoMessage($notification))
            ->setData($data)
            ->setChannelId($this->channelName)
            ->setBadge(0)
            ->playSound();

        (new Expo)->send($this->message)->to($this->recipients)->push();
    }
}

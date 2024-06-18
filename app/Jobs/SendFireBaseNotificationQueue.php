<?php

namespace App\Jobs;

use App\Support\Firebase;
use ExpoSDK\Exceptions\ExpoException;
use ExpoSDK\Exceptions\ExpoMessageException;
use ExpoSDK\Exceptions\InvalidTokensException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendFireBaseNotificationQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public $token, public $notification, public $data = [])
    {
        if (count($data) < 1) {
            $this->data = $this->notification;
        }
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (! is_null($this->token)) {
            try {
                $firebase = new Firebase($this->token);
                $firebase->sendMessageWithToken($this->notification, $this->notification);
            }
            catch (ExpoException|ExpoMessageException|InvalidTokensException $e) {
                logExceptionErrorMessage('SendFireBaseNotificationQueue',$e,[],'error');
            }
        }
    }
}

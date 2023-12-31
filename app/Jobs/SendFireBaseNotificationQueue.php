<?php

namespace App\Jobs;

use App\Support\Firebase;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\MessagingException;

class SendFireBaseNotificationQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public $token,public $notification,public $data = [])
    {
        if(count($data) < 1 ){
            $this->data = $this->notification;
        }
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $firebase = new Firebase($this->token);
        try {
            $firebase->sendMessageWithToken($this->notification, $this->notification);
        }
        catch (MessagingException|FirebaseException $e) {
            logExceptionErrorMessage('SendFireBaseNotificationQueue', $e);
        }
    }
}

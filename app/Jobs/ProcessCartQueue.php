<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Src\Wallets\Payments\Domain\Actions\UpdateCartWithOrderNumber;
use Src\Wallets\Payments\Domain\Services\SubscriptionService;

class ProcessCartQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public $carts,public $user,public $request)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach($this->carts as $cart) {
            DB::beginTransaction();
            $subscription = new SubscriptionService($cart,$this->user,$this->request);
            $subscription->execute();
            $cart = new UpdateCartWithOrderNumber($cart,$this->request['order_number']);
            $cart->execute();
            logExceptionErrorMessage(
                'ProcessCartQueue',
                null,
                [$cart]
            );
            DB::commit();
        }
    }
}

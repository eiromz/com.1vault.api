<?php

namespace App\Jobs;

use App\Models\Account;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AccountBalanceUpdateQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public $balance_before,public $balance_after, public $accountBalance)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $fetchAccount = Account::query()
                ->where('customer_id','=',$this->accountBalance->customer_id)
                ->firstOrFail();

            $fetchAccount->balance_before = $this->balance_before;
            $fetchAccount->balance_after  = $this->balance_after;

            if(!$fetchAccount->save()){
                logExceptionErrorMessage('AccountBalanceUpdateQueue',null,[
                    'customer_id' => $this->accountBalance->customer_id,
                    'balance_before' => $this->balance_before,
                    'balance_after' => $this->balance_after,
                ]);
            }

            logExceptionErrorMessage('AccountBalanceUpdateQueue',null,[
                'customer_id' => $this->accountBalance->customer_id,
                'balance_before' => $this->balance_before,
                'balance_after' => $this->balance_after,
            ]);

        } catch (Exception $e) {
            logExceptionErrorMessage('AccountBalanceUpdateQueue',$e);
        }
    }
}

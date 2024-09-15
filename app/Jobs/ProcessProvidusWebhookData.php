<?php

namespace App\Jobs;

use App\Models\Profile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Src\Wallets\Payments\Domain\Actions\CreditJournalAction;
use Src\Wallets\Payments\Domain\Actions\GetAccountInstance;

class ProcessProvidusWebhookData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 2;

    public $failOnTimeout = true;

    /**
     * Create a new job instance.
     */
    public function __construct(public $data)
    {
        $this->onQueue('providus');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $profile = Profile::query()
            ->where('account_number', '=', $this->data['accountNumber'])
            ->with('customer')
            ->firstOrFail();

        //get the profile and validate

        //        $account = GetAccountInstance::getActiveInstance($profile);
        //
        //        $newJournalBalance = CreditJournalAction::execute($request, $account, 'webhook');
        //
        //        SendFireBaseNotificationQueue::dispatch($profile->customer->firebase_token ?? null, $this->notification);
        //
        //        AccountBalanceUpdateQueue::dispatch(
        //            $newJournalBalance->balance_before, $newJournalBalance->balance_after, $account);
    }
}

<?php

namespace Src\Wallets\Payments\Domain\Services;

use App\Exceptions\BaseException;
use App\Jobs\AccountBalanceUpdateQueue;
use App\Jobs\SendFireBaseNotificationQueue;
use App\Models\Customer;
use App\Models\Journal;
use Symfony\Component\HttpFoundation\Response;

class JournalWalletCreditService
{
    public object $accountInstance;

    public $request;

    public $journal;

    public array $creationKeys = ['amount', 'trx_ref',
        'debit', 'credit', 'label', 'source', 'balance_before', 'balance_after', 'customer_id', 'remark',
    ];

    public function __construct($accountInstance, $request = null)
    {
        $this->accountInstance = $accountInstance;
        $this->request = $request;
    }

    public function calculateNewBalance()
    {
        return ($this->accountInstance->balance_after + $this->request->amount);
    }

    /**
     * @throws BaseException
     */
    public function credit()
    {
        $fullname = auth()->user()->profile->fullname;

        $this->request->merge([
            'balance_before'    => $this->accountInstance->balance_after,
            'balance_after'     => $this->calculateNewBalance(),
            'customer_id'       => $this->accountInstance->customer_id,
            'trx_ref'           => generateTransactionReference(),
            'debit'             => false,
            'credit'            => true,
            'label'             => 'Transfer',
            'source'        => $fullname,
            'remark' => "Transfer from {$fullname}"
        ]);

        $this->journal = Journal::query()->create($this->request->only($this->creationKeys));

        if (!$this->journal) {
            throw new BaseException('Failed to process transaction', Response::HTTP_BAD_REQUEST);
        }

        return $this;
    }

    public function notify()
    {
        $this->firebase();

        return $this;
    }

    private function firebase(): void
    {
        if(!is_null($this->request?->profile?->firebase_token)){
            $notification = [
                'title' => 'Credit Notification',
                'body' => "Your account has been credited the sum of {$this->request->amount}",
            ];

            SendFireBaseNotificationQueue::dispatch($this->request->profile->firebase_token, $notification);
        }
    }

    public function updateBalanceQueue(): void
    {
        AccountBalanceUpdateQueue::dispatch(
            $this->request->balance_before, $this->request->balance_after, $this->accountInstance);
    }
}

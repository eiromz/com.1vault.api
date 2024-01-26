<?php

namespace Src\Wallets\Payments\Domain\Services;

use App\Jobs\AccountBalanceUpdateQueue;
use App\Jobs\SendFireBaseNotificationQueue;
use App\Models\Customer;
use App\Models\Journal;
use Symfony\Component\HttpFoundation\Response;

class JournalWalletCreditService
{
    public object $accountInstance;

    public $request;

    public $creationKeys = ['amount', 'trx_ref',
        'debit', 'credit', 'label', 'source', 'balance_before', 'balance_after', 'customer_id',
    ];

    public function __construct($accountInstance, $request = null)
    {
        $this->accountInstance = $accountInstance;
        $this->request = $request;
    }

    public function calculateNewBalance()
    {
        return $this->accountInstance->balance_after + $this->request->amount;
    }

    public function credit()
    {
        $this->request->merge([
            'balance_before' => $this->accountInstance->balance_after,
            'balance_after' => $this->calculateNewBalance(),
            'customer_id' => $this->accountInstance->customer_id,
            'trx_ref' => generateTransactionReference(),
            'debit' => false,
            'credit' => true,
        ]);

        if (! Journal::query()->create($this->request->only($this->creationKeys))) {
            throw new Exception('Failed to process transaction', Response::HTTP_BAD_REQUEST);
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
        $notification = [
            'title' => 'Credit Notification',
            'body' => "Your account has been credited the sum of {$this->request->amount}",
        ];

        SendFireBaseNotificationQueue::dispatch($this->profile()->firebase_token ?? null, $notification);
    }

    public function profile(): ?object
    {
        return Customer::query()->with('profile')
            ->where('id', $this->accountInstance->customer_id)->first();
    }

    public function updateBalanceQueue(): void
    {
        AccountBalanceUpdateQueue::dispatch(
            $this->request->balance_before, $this->request->balance_after, $this->accountInstance);
    }
}

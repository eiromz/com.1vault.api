<?php

namespace Src\Wallets\Payments\Domain\Services;

use App\Exceptions\BaseException;
use App\Exceptions\InsufficientBalance;
use App\Jobs\AccountBalanceUpdateQueue;
use App\Jobs\SendFireBaseNotificationQueue;
use App\Models\Journal;
use Exception;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class JournalWalletDebitService
{
    public object $accountInstance;

    public $request;

    public $journal;

    public $creationKeys = ['amount', 'trx_ref',
        'debit', 'credit', 'label', 'source', 'balance_before', 'balance_after', 'customer_id',
    ];

    public function __construct($accountInstance, $request = null)
    {
        $this->accountInstance = $accountInstance;
        $this->request = $request;
    }

    /**
     * @throws InsufficientBalance
     */
    public function checkBalance()
    {
        if ($this->request->amount > $this->accountInstance->balance_after) {
            throw new InsufficientBalance('Insufficient Balance', Response::HTTP_BAD_REQUEST);
        }

        return $this;
    }

    public function calculateNewBalance()
    {
        return $this->accountInstance->balance_after - $this->request->amount;
    }

    public function debit()
    {
        $this->request->merge([
            'balance_before' => $this->accountInstance->balance_after,
            'balance_after' => $this->calculateNewBalance(),
            'customer_id' => auth()->user()->id,
            'debit' => true,
            'credit' => false,
            'trx_ref' => generateTransactionReference(),
            'label' => 'Transfer',
            'source' => auth()->user()->profile->fullname,
        ]);

        $this->journal = Journal::query()->create($this->request->only($this->creationKeys));

        if (! $this->journal) {
            throw new BaseException('Failed to process transaction', Response::HTTP_BAD_REQUEST);
        }

        return $this;
    }

    public function notify()
    {
        $this->firebase();
        $this->email();

        return $this;
    }

    private function firebase(): void
    {
        $notification = [
            'title' => 'Debit Notification',
            'body' => "Your account has been debited the sum of {$this->request->amount}",
        ];

        SendFireBaseNotificationQueue::dispatch(auth()->user()->firebase_token ?? null, $notification);
    }

    //TODO : write email for notifying a person about a transction on his/her account.
    private function email()
    {
    }

    public function updateBalanceQueue(): void
    {
        AccountBalanceUpdateQueue::dispatch(
            $this->request->balance_before, $this->request->balance_after, $this->accountInstance);
    }

    /**
     * @throws Exception
     */
    public function validateTransactionPin(): void
    {
        if (! Hash::check($this->request->transaction_pin, auth()->user()->transaction_pin)) {
            throw new BaseException('Invalid Transaction Pin', Response::HTTP_BAD_REQUEST);
        }
    }
}

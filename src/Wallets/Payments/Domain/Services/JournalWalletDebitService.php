<?php

namespace Src\Wallets\Payments\Domain\Services;

use App\Exceptions\BaseException;
use App\Exceptions\InsufficientBalance;
use App\Jobs\AccountBalanceUpdateQueue;
use App\Jobs\SaveBeneficiaryQueue;
use App\Jobs\SendFireBaseNotificationQueue;
use App\Models\Beneficiary;
use App\Models\Journal;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class JournalWalletDebitService
{
    public object $accountInstance;

    public mixed $request;

    public mixed $journal;

    public array $creationKeys = [
        'amount', 'trx_ref',
        'debit', 'credit', 'label', 'source', 'balance_before', 'balance_after', 'customer_id', 'remark',
    ];

    public function __construct($accountInstance, $request = null)
    {
        $this->accountInstance = $accountInstance;
        $this->request = $request;
    }

    public function checkBalance(): static
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

    public function debit($label = 'Transfer Out'): static
    {
        $this->request->merge([
            'balance_before' => $this->accountInstance->balance_after,
            'balance_after' => $this->calculateNewBalance(),
            'customer_id' => auth()->user()->id,
            'debit' => true,
            'credit' => false,
            'trx_ref' => $this->request->transactionReference ?? generateTransactionReference(),
            'label' => $label,
            'source' => auth()->user()->profile->fullname,
        ]);

        $this->journal = Journal::query()->create($this->request->only($this->creationKeys));

        if (! $this->journal) {
            throw new BaseException('Failed to process transaction', Response::HTTP_BAD_REQUEST);
        }

        return $this;
    }

    public function notify(): static
    {
        $this->firebase();
        $this->email();

        return $this;
    }

    private function firebase(): void
    {
        if (! is_null($this->request?->profile?->firebase_token)) {
            $notification = [
                'title' => 'Debit Notification',
                'body' => "Your account has been debited the sum of {$this->request->amount}",
            ];

            SendFireBaseNotificationQueue::dispatch(auth()->user()->firebase_token ?? null, $notification);
        }
    }

    //TODO : write email for notifying a person about a transaction on his/her account.
    private function email() {}

    public function updateBalanceQueue(): void
    {
        AccountBalanceUpdateQueue::dispatch(
            $this->request->balance_before,
            $this->request->balance_after,
            $this->accountInstance
        );
    }

    public function validateTransactionPin(): void
    {
        if (! Hash::check($this->request->transaction_pin, auth()->user()->transaction_pin)) {
            throw new BaseException('Invalid Transaction Pin', Response::HTTP_BAD_REQUEST);
        }
    }

    public function saveBeneficiary(): static
    {
        $beneficiary = ($this->request->trx_type === '1vault') ? $this->providusBankBeneficiary() : $this->nipBankBeneficiary();

        $beneficiaryExist = Beneficiary::query()->where($beneficiary)->exists();

        if (! $beneficiaryExist && $this->request->boolean('saveBeneficiary')) {
            SaveBeneficiaryQueue::dispatch($beneficiary)->delay(now()->addMinute());
        }

        return $this;
    }

    private function providusBankBeneficiary(): array
    {
        return [
            'type' => $this->request->trx_type,
            'customer_id' => auth()->user()->id,
            'bank_code' => Journal::BANK_CODE,
            'bank_name' => Journal::BANK_NAME,
            'bank_account_number' => $this->request->profile->account_number,
            'bank_account_name' => $this->request->profile->fullname,
        ];
    }

    private function nipBankBeneficiary(): array
    {
        return [
            'type' => $this->request->trx_type,
            'customer_id' => auth()->user()->id,
            'bank_code' => $this->request->beneficiaryBank,
            'bank_name' => $this->request->beneficiaryBankName,
            'bank_account_number' => $this->request->beneficiaryAccountNumber,
            'bank_account_name' => $this->request->beneficiaryAccountName,
        ];
    }
}

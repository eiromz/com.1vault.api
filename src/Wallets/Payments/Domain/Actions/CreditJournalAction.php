<?php

namespace Src\Wallets\Payments\Domain\Actions;

use App\Models\Journal;

class CreditJournalAction
{
    public static function execute($params, $currentBalance, $origin = 'webhook')
    {
        try {

            $data = [];

            if ($origin === 'webhook') {
                $data = (new self)->webhook($params, $currentBalance);
            }

            \DB::beginTransaction();

            $journal = Journal::query()->create($data);

            \DB::commit();

            return $journal;
        } catch (\Exception $e) {
            \DB::rollBack();
            logExceptionErrorMessage('CreditJournalAction', $e, []);
        }
    }

    public function webhook($params, $currentBalance)
    {
        $c = $currentBalance;
        $p = $params;
        $newBalance = ($c->balance_after + $p->transactionAmount);

        return [
            'customer_id' => $c->customer_id,
            'trx_ref' => $p->settlementId,
            'session_id' => $p->sessionId,
            'amount' => $p->transactionAmount,
            'commission' => 0,
            'debit' => false,
            'credit' => true,
            'balance_before' => $c->balance_after,
            'balance_after' => $newBalance,
            'label' => 'Transfer',
            'source' => $p->sourceAccountName ?? 'N/A',
            'payload' => json_encode($params->all()),
        ];
    }

    //Account model exists for transaction
}

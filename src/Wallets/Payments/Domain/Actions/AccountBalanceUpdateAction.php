<?php

namespace Src\Wallets\Payments\Domain\Actions;

use App\Models\Journal;

class AccountBalanceUpdateAction
{
    public static function execute($balance_before,$balance_after,$account)
    {
        try {

            $data = [];

            if ($origin === 'webhook') {
                $data = (new self)->webhook($params, $currentBalance);
            }

            //        if($origin === 'service'){
            //            $data = (new self)->service(params);
            //        }

            \DB::beginTransaction();

            $journal = Journal::query()->create($data);

            \DB::commit();

            return $journal;
        } catch (\Exception $e) {
            \DB::rollBack();
            logExceptionErrorMessage('CreditJournalAction', $e, []);
        }
    }

}

//automated account update for a user once a transaction happens

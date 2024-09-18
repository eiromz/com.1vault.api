<?php

namespace Src\Wallets\Payments\Domain\Actions;

use App\Models\Account;
use App\Models\Journal;
use Illuminate\Database\Eloquent\Model;

class GetAccountInstance
{
    /**
     * Check if a customer has made any transaction
     * once there is no transaction go and get the account model object
     *
     * @param $profile
     * @return Model
     */
    public static function getActiveInstance($profile): Model
    {
        $model = Journal::query()
            ->where('customer_id', '=', $profile->customer_id)
            ->latest()
            ->first();

        if (is_null($model)) {
            $model = Account::query()
                ->where('customer_id', '=', $profile->customer_id)
                ->first();
        }

        return $model;
    }
}

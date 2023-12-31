<?php

namespace Src\Wallets\Payments\Domain\Actions;

use App\Models\Account;
use App\Models\Journal;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class GetAccountInstance
{
    public $model;

    /**
     * Check if a customer has made any transaction
     * once there is no transaction go and get the account model object
     * @param $account_number
     * @return Model
     */
    public static function getActiveInstance($account_number): Model
    {
        $profile = Profile::query()
            ->where('account_number','=',$account_number)
            ->with('customer')
            ->firstOrFail();

        $model = Journal::query()
            ->where('customer_id','=',$profile->customer_id)
            ->latest()
            ->first();

        if(is_null($model)) {
            $model = Account::query()
                ->where('customer_id','=',$profile->customer_id)
                ->first();
        }

        return $model;
    }
}

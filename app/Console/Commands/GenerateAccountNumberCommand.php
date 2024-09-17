<?php

namespace App\Console\Commands;

use App\Jobs\GenerateAccountNumberQueue;
use App\Models\Customer;
use App\Models\KnowYourCustomer;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class GenerateAccountNumberCommand extends Command
{
    protected $signature = 'app:generate-account-number-command';

    protected $description = 'Generate account number for approved customers';

    public function handle()
    {
        $customers = Customer::query()
            ->whereHas('profile', function (Builder $query) {
                $query->whereNull('account_number');
            })
            ->whereHas('knowYourCustomer', function (Builder $query) {
                $query->whereNotNull('bvn')->where('status', '=', KnowYourCustomer::ACTIVE);
            })->get();

        if (is_null($customers)) {
            logger('GenerateAccountNumberCommand', ['customers' => $customers]);
        }

        foreach ($customers as $customer) {
            GenerateAccountNumberQueue::dispatch($customer, $customer->profile, $customer->knowYourCustomer)->delay(now()->addMinute());
        }
    }
}

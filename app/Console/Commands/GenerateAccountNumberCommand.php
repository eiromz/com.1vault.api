<?php

namespace App\Console\Commands;

use App\Jobs\GenerateAccountNumberQueue;
use App\Models\Customer;
use App\Models\KnowYourCustomer;
use Illuminate\Console\Command;

class GenerateAccountNumberCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-account-number-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate account number for approved customers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //run a job to generate the account number for a user
        $customers = Customer::query()->whereHas('profile')->whereHas('knowYourCustomer')->get();

        foreach ($customers as $customer) {
            if ($customer->knowYourCustomer->status !== KnowYourCustomer::ACTIVE) {
                return;
            }

            GenerateAccountNumberQueue::dispatch($customer, $customer->profile, $customer->knowYourCustomer)->delay(now()->addMinute());
        }
    }
}

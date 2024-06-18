<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Account;
use App\Models\Beneficiary;
use App\Models\Business;
use App\Models\Client;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Journal;
use App\Models\KnowYourCustomer;
use App\Models\Profile;
use App\Models\Service;
use App\Models\State;
use App\Models\Subscription;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Src\Services\App\Enum\BillingCycle;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $now = now();
        $this->call([
            CountrySeeder::class,
            StateSeeder::class,
            SocialMediaSubscriptionSeeder::class,
            StoreFrontSubscriptionSeeder::class,
            BusinessAnalyticsSubscriptionSeeder::class,
            BusinessRegistrationSeeder::class
        ]);

        $state = State::query()->where('name', '=', 'Lagos')
            ->first();

        Customer::factory()->create([
            'password' => Hash::make('sampleTim@123'),
            'phone_number' => '0810379'.fake()->randomNumber(5, true),
            'otp_expires_at' => $now,
            'email' => 'crayoluadmin@gmail.com',
            'transaction_pin' => Hash::make('123456'),
            'role' => 'admin',
        ]);

        $customer = Customer::factory()->create([
            'password' => Hash::make('sampleTim@123'),
            'phone_number' => '08103797739',
            'otp_expires_at' => $now,
            'email' => 'crayolu@gmail.com',
            'transaction_pin' => Hash::make('123456'),
            'firebase_token' => 'ExponentPushToken[sgq4doHKnMMLwP9VBI0zDw]',
        ]);

        Profile::factory()->create([
            'customer_id' => $customer->id,
            'account_number' => '9977581536',
            'state_id' => $state->id,
        ]);

        Account::factory()->create([
            'customer_id' => $customer->id,
            'balance_after' => 10000000,
        ]);

        KnowYourCustomer::factory()->create([
            'customer_id' => $customer->id,
        ]);

        $business = Business::factory()->count(2)->create([
            'state_id' => $state->id,
            'customer_id' => $customer->id,
        ]);

        $client = Client::factory()->create([
            'business_id' => $business->first()->id,
            'customer_id' => $customer->id,
            'fullname' => 'Apostle Atokolos',
        ]);

        Invoice::factory()->count(3)->create([
            'business_id' => $client->business_id,
            'customer_id' => $customer->id,
            'client_id' => $client->id,
        ]);

        $service = Service::query()->where('category', '=', 'store_front')->first();

        Subscription::factory()->count(2)->create([
            'customer_id' => $customer->id,
            'service_id' => $service->id,
            'amount' => $service->amount,
            'subscription_date' => $now,
            'expiration_date' => determineExpirationDate($now, $service->billing_cycle),
        ]);

        foreach ($this->journals($customer) as $journal) {
            Journal::factory()->create($journal);
        }

        Beneficiary::factory()->count(30)->create([
            'customer_id' => $customer->id
        ]);

        $this->customer2($state);
    }

    private function journals($customer)
    {
        return [
            [
                'customer_id' => $customer->id,
                'credit' => true,
                'amount' => 10000000,
                'balance_before' => 0,
                'balance_after' => 10000000,
                'label' => 'Transfer In',
                'remark' => 'I made this payment'
            ],
            [
                'customer_id' => $customer->id,
                'credit' => true,
                'amount' => 500000,
                'balance_before' => 10000000,
                'balance_after' => 9500000,
                'label' => 'Transfer Out',
                'remark' => 'I made this transfer out'
            ],
            [
                'customer_id' => $customer->id,
                'debit' => true,
                'amount' => 200000,
                'balance_before' => 9500000,
                'balance_after' => 9300000,
                'label' => 'Airtime',
                'remark' => 'I made this transfer out for the transaction'
            ],
            [
                'customer_id' => $customer->id,
                'debit' => true,
                'amount' => 350000,
                'balance_before' => 9300000,
                'balance_after' => 8950000,
                'label' => 'Electricity',
                'remark' => 'I made this transfer out for this one time'
            ],
            [
                'customer_id' => $customer->id,
                'debit' => true,
                'amount' => 150000,
                'balance_before' => 8950000,
                'balance_after' => 8800000,
                'label' => 'Data',
                'remark' => 'I made this transfer out for boel'
            ],
            [
                'customer_id' => $customer->id,
                'debit' => true,
                'amount' => 150000,
                'balance_before' => 8800000,
                'balance_after' => 8650000,
                'label' => 'Cable Tv',
                'remark' => 'I made this transfer out for cable tv'
            ],
            [
                'customer_id' => $customer->id,
                'debit' => true,
                'amount' => 250000,
                'balance_before' => 8650000,
                'balance_after' => 8400000,
                'label' => 'Cable Tv',
                'remark' => 'I made this transfer out for cable tv'
            ],
        ];
    }

    private function customer2($state): void
    {
        $customer = Customer::factory()->create([
            'password' => Hash::make('sampleTim@123'),
            'otp_expires_at' => now(),
            'email' => 'crayolu200@gmail.com',
            'transaction_pin' => Hash::make('123456'),
            //'firebase_token' => 'fwb71Cn3N0TLgZR8yH97r-:APA91bHAsd8RCraGU2aRdwqLgjRztSc52NOw6ibxmfjP0w4GioDACV-b-iCnqXHPxkU9FAl-bDO2tZHz53rrRtnaXgcI_DKqX0BYvY-uPniSoXXMkjlOI-KzIAPiNF0TDppFopnlGppj',
        ]);

        Profile::factory()->create([
            'customer_id' => $customer->id,
            'account_number' => '9977581538',
            'state_id' => $state->id,
        ]);

        $account = Account::factory()->create([
            'customer_id' => $customer->id,
        ]);

        $kyc = KnowYourCustomer::factory()->create([
            'customer_id' => $customer->id,
        ]);
    }
}

<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Account;
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
        ]);

        $state = State::query()
            ->where('name', '=', 'Lagos')->first();

        //Create admin
        $admin = Customer::factory()->create([
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
            //'firebase_token' => 'fwb71Cn3N0TLgZR8yH97r-:APA91bHAsd8RCraGU2aRdwqLgjRztSc52NOw6ibxmfjP0w4GioDACV-b-iCnqXHPxkU9FAl-bDO2tZHz53rrRtnaXgcI_DKqX0BYvY-uPniSoXXMkjlOI-KzIAPiNF0TDppFopnlGppj',
        ]);

        Profile::factory()->create([
            'customer_id' => $customer->id,
            'account_number' => '9977581536',
            'state_id' => $state->id,
        ]);

        Account::factory()->create([
            'customer_id' => $customer->id,
            'balance_after' => 10000000
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

        Service::query()->create($this->business_name());
        Service::query()->create($this->business_llc());
        Service::query()->create($this->social_media_1());
        Service::query()->create($this->social_media_2());
        Service::query()->create($this->store_front_1());
        Service::query()->create($this->store_front_2());

        $service =  Service::query()->where('category','=','store_front')->first();

        Subscription::factory()->create([
            'customer_id'       => $customer->id,
            'service_id'        => $service->id,
            'amount'            => $service->amount,
            'subscription_date' => $now,
            'expiration_date'   => determineExpirationDate($now,$service->billing_cycle)
        ]);

        Journal::factory()->count(3)->create(['customer_id' => $customer->id]);

        $this->customer2($state);
    }
    private function business_name(): array
    {
        return [
            'title' => 'Register a business name in Nigeria',
            'type' => 'business_name',
            'provider' => '1vault',
            'description' => 'Let our experts take the stress off you when it comes to registering your business name',
            'amount' => 20000,
            'commission' => 0,
            'is_recurring' => 0,
            'billing_cycle' => 'one-time',
            'is_request' => 1,
            'discount' => 0,
            'status' => 1,
            'category' => 'business_registration',
            'benefit' => [
                'content_creation',
            ],
            'quantity' => 0,
        ];
    }
    private function social_media_1(): array
    {
        return [
            'title' => 'Basic Plan',
            'type' => 'social_media_subscription',
            'provider' => '1vault',
            'description' => 'N/A',
            'amount' => 50000,
            'commission' => 0,
            'is_recurring' => 1,
            'billing_cycle' => BillingCycle::MONTHLY->value,
            'is_request' => 0,
            'discount' => 0,
            'status' => 1,
            'category' => 'social_media',
            'benefit' => [
                'Content Generation',
                'Graphic Design',
                'Sponsored ads',
                'Posting across Instagram,Facebook and Twitter',
            ],
            'quantity' => 8,
        ];
    }
    private function social_media_2(): array
    {
        return [
            'title' => 'Basic Plan',
            'type' => 'service.social_media_subscription',
            'provider' => '1vault',
            'description' => 'N/A',
            'amount' => 600000,
            'commission' => 0,
            'is_recurring' => 1,
            'billing_cycle' => BillingCycle::YEARLY->value,
            'is_request' => 0,
            'discount' => 0,
            'status' => 1,
            'category' => 'social_media',
            'benefit' => [
                'Content Generation',
                'Graphic Design',
                'Sponsored ads',
                'Posting across Instagram,Facebook and Twitter',
            ],
            'quantity' => 8,
        ];
    }
    private function store_front_1(): array
    {
        return [
            'title' => 'Basic Plan',
            'type' => 'service.store_front_subscription',
            'provider' => '1vault',
            'description' => 'N/A',
            'amount' => 4500,
            'commission' => 0,
            'is_recurring' => 1,
            'billing_cycle' => BillingCycle::QUARTERLY->value,
            'is_request' => 0,
            'discount' => 0,
            'status' => 1,
            'category' => 'store_front',
            'benefit' => [
                'Store url created for your shop',
                'Receive payments directly to your 1vault wallet',
                '50 product listings',
                'No hidden charges or commissions',
            ],
            'quantity' => 0,
        ];
    }
    private function store_front_2(): array
    {
        return [
            'title' => 'Basic Plan',
            'type' => 'service.store_front_subscription',
            'provider' => '1vault',
            'description' => 'N/A',
            'amount' => 15000,
            'commission' => 0,
            'is_recurring' => 1,
            'billing_cycle' => BillingCycle::YEARLY->value,
            'is_request' => 0,
            'discount' => 0,
            'status' => 1,
            'category' => 'store_front',
            'benefit' => [
                'Store url created for your shop',
                'Receive payments directly to your 1vault wallet',
                '50 product listings',
                'No hidden charges or commissions',
            ],
            'quantity' => 0,
        ];
    }
    private function business_llc(): array
    {
        return [
            'title' => 'Register a Limited Liability Company in Nigeria',
            'type' => 'business_llc',
            'provider' => '1vault',
            'description' => 'Let our experts take the stress off you when it comes to registering your LLC name',
            'amount' => 70000,
            'commission' => 0,
            'is_recurring' => 0,
            'billing_cycle' => 'one-time',
            'is_request' => 1,
            'discount' => 0,
            'status' => 1,
            'category' => 'business_registration',
            'benefit' => [
                'content_creation',
            ],
            'quantity' => 0,
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

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
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
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
            'otp_expires_at' => now(),
            'email' => 'crayoluadmin@gmail.com',
            'transaction_pin' => Hash::make('123456'),
            'role' => 'admin',
        ]);

        $customer = Customer::factory()->create([
            'password' => Hash::make('sampleTim@123'),
            'phone_number' => '08103797739',
            'otp_expires_at' => now(),
            'email' => 'crayolu@gmail.com',
            'transaction_pin' => Hash::make('123456'),
            //'firebase_token' => 'fwb71Cn3N0TLgZR8yH97r-:APA91bHAsd8RCraGU2aRdwqLgjRztSc52NOw6ibxmfjP0w4GioDACV-b-iCnqXHPxkU9FAl-bDO2tZHz53rrRtnaXgcI_DKqX0BYvY-uPniSoXXMkjlOI-KzIAPiNF0TDppFopnlGppj',
        ]);

        Profile::factory()->create([
            'customer_id' => $customer->id,
            'account_number' => '9977581536',
            'state_id' => $state->id,
        ]);

        $account = Account::factory()->create([
            'customer_id' => $customer->id,
        ]);

        $kyc = KnowYourCustomer::factory()->create([
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

        $invoice = Invoice::factory()->count(3)->create([
            'business_id' => $client->business_id,
            'customer_id' => $customer->id,
            'client_id' => $client->id,
        ]);

        $service = Service::factory()->count(3)->create([
            'category' => 'social_media',
        ]);
        Service::query()->create($this->business_name());
        Service::query()->create($this->business_llc());

        $journal = Journal::factory()->count(3)->create([
            'customer_id' => $customer->id,
        ]);
    }

    public function business_name(): array
    {
        return [
            'title' => 'Business Name Registration',
            'type' => 'business_name',
            'provider' => '1vault',
            'description' => fake()->lastName,
            'amount' => fake()->numberBetween(1000, 100000),
            'commission' => fake()->numberBetween(1000, 100000),
            'is_recurring' => 0,
            'billing_cycle' => 'one-time',
            'is_request' => 1,
            'discount' => fake()->numberBetween(1000, 100000),
            'status' => fake()->boolean,
            'category' => 'business_registration',
            'benefit' => [
                'content_creation',
            ],
            'quantity' => 0,
        ];
    }

    public function business_llc(): array
    {
        return [
            'title' => 'Register a Limited Liability Company in Nigeria',
            'type' => 'business_llc',
            'provider' => '1vault',
            'description' => fake()->lastName,
            'amount' => fake()->numberBetween(1000, 100000),
            'commission' => fake()->numberBetween(1000, 100000),
            'is_recurring' => 0,
            'billing_cycle' => 'one-time',
            'is_request' => 1,
            'discount' => fake()->numberBetween(1000, 100000),
            'status' => fake()->boolean,
            'category' => 'business_registration',
            'benefit' => [
                'content_creation',
            ],
            'quantity' => 0,
        ];
    }

}

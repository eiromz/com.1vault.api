<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Account;
use App\Models\Customer;
use App\Models\Journal;
use App\Models\KnowYourCustomer;
use App\Models\Profile;
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
            'firebase_token' => "fwb71Cn3N0TLgZR8yH97r-:APA91bHAsd8RCraGU2aRdwqLgjRztSc52NOw6ibxmfjP0w4GioDACV-b-iCnqXHPxkU9FAl-bDO2tZHz53rrRtnaXgcI_DKqX0BYvY-uPniSoXXMkjlOI-KzIAPiNF0TDppFopnlGppj"
        ]);

        Profile::factory()->create([
            'customer_id'       => $customer->id,
            'account_number'    => '9977581536'
        ]);

        $account = Account::factory()->create([
            'customer_id' => $customer->id,
        ]);

        $kyc = KnowYourCustomer::factory()->create([
            'customer_id' => $customer->id,
        ]);

//        $journal = Journal::factory()->create([
//            'customer_id' => $customer->id,
//        ]);
    }
}

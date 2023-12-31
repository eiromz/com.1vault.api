<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Account;
use App\Models\Customer;
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
        //         \App\Models\User::factory(10)->create();
        //
        //         \App\Models\User::factory()->create([
        //             'name' => 'Test User',
        //             'email' => 'test@example.com',
        //         ]);

        $this->call([
            CountrySeeder::class,
            StateSeeder::class,
        ]);

        $customer = Customer::factory()->create([
            'password' => Hash::make('sampleTim@123'),
            'phone_number' => '08103797739',
            'otp_expires_at' => now(),
            'email' => 'crayolu@gmail.com',
            'transaction_pin' => Hash::make('123456'),
        ]);

        Profile::factory()->create([
            'customer_id'       => $customer->id,
        ]);

        Account::factory()->create([
            'customer_id' => $customer->id,
        ]);

        $admin = Customer::factory()->create([
            'password' => Hash::make('sampleTim@123'),
            'phone_number' => '0810379'.fake()->randomNumber(5, true),
            'otp_expires_at' => now(),
            'email' => 'crayoluadmin@gmail.com',
            'transaction_pin' => Hash::make('123456'),
            'role' => 'admin',
        ]);
    }
}

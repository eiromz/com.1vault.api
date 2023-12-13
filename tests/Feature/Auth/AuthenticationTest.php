<?php

use App\Models\Customer;
use App\Models\Profile;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class)->in('Feature');

describe('Profile Routes', function(){
    beforeEach(function () {
        $this->seed(DatabaseSeeder::class);

        $this->customer = Customer::where('email','crayolu@gmail.com')->with('profile')->first();

    });
    test("Customer can submit kyc information", function(){
        //selfie
        //docs
    });
    test('Customers can forgot their pin',function(){
        $response = $this->actingAs($this->customer)->post('/api/v1/profile/transaction-pin',[
            'type' => 'forgot',
            'password'   => 'sampleTim@123',
            'pin'   => '123455',
            'pin_confirmation'   => '123455'
        ]);
        $response->dump();
        expect($response->status())->toBe(200);
    });
    test('Customers can create a transaction pin',function(){
        $response = $this->actingAs($this->customer)->post('/api/v1/profile/transaction-pin',[
            'type' => 'create',
            'pin'   => '123455',
            'pin_confirmation'   => '123455'
        ]);

        $response->dump();
        expect($response->status())->toBe(200);
    });
    test('Customers can change their transaction pin',function(){
        $response = $this->actingAs($this->customer)->post('/api/v1/profile/transaction-pin',[
            'type' => 'change',
            'current_pin'   => '123456',
            'pin'   => '123455',
            'pin_confirmation'   => '123455'
        ]);
        $response->dump();
        expect($response->status())->toBe(200);
    });
    test('Customers can change their password when logged in',function(){
        $response = $this->actingAs($this->customer)->post('/api/v1/profile/change-password',[
            'current_password'   => 'sampleTim@123',
            'password'   => 'sampleTim@1234',
            'password_confirmation'   => 'sampleTim@1234'
        ]);
        $response->dump();
        expect($response->status())->toBe(200);
    });
    test('Customers can update their profile',function(){
        $response = $this->actingAs($this->customer)->post('/api/v1/profile',[
            'firstname'      => fake()->firstName,
            'lastname'       => fake()->lastName,
            'phone_number'   => "08139691937",
            'firebase_token' => 'wekjnskdjnkfndfknjsdf',
            'business_name'  => 'Olubekun',
            'business_physical_address' => 'Olubekun',
            'business_zip_code' => 'Olubekun',
            'business_logo'  => 'Olubekun',
        ]);
        $response->dump();
        expect($response->status())->toBe(200);
    });
    test('Customers can view their profile',function(){
        $response = $this->actingAs($this->customer)->get('/api/v1/profile');
        expect($response->status())->toBe(200);
    });
    test('Customers can delete account', function(){
        $response = $this->actingAs($this->customer)->post('/api/v1/profile/delete-account');

        expect($response->status())->toBe(200);
    });
});

describe('Auth Routes', function () {
    beforeEach(function () {
        $this->seed(DatabaseSeeder::class);

        $this->customer = Customer::factory()->create([
            'password' => Hash::make('sampleTim@123'),
            'phone_number' => '08103797739',
            'otp_expires_at' => now(),
            'email' => 'crayolu@gmail.com'
        ]);

        $this->profile =  Profile::factory()->create([
            'customer_id' => $this->customer->id,
            'firstname' => 'Babatunde',
        ]);

    });

    test('Customers verify otp', function () {

        $response = $this->post('/api/v1/auth/verify-otp', [
            'email' => $this->customer->email,
            'otp' => $this->customer->otp,
        ]);

        $response->dump();

        expect($response->status())->toBe(200);
    });

    test('Customers reset password using otp', function () {

        $response = $this->post('/api/v1/auth/reset-password', [
            'email'     => $this->customer->email,
            'password'  => "sampleTim@123",
            'password_confirmation' => "sampleTim@123",
        ]);

        expect($response->status())->toBe(200);
    });

    test('Customers forgot password', function () {

        $response = $this->post('/api/v1/auth/forgot-password', [
            'email' => $this->customer->email,
        ]);

        expect($response->status())->toBe(200);
    });

    test('Customers can logout', function () {

        //    Sanctum::actingAs(
        //        $customer,
        //        Customer::OWNER_ABILITIES
        //    );

        $response = $this->actingAs($this->customer)->post('/api/v1/auth/logout');

        expect($response->status())->toBe(200);
    });

    test('Customers can login', function () {
        $response = $this->post('/api/v1/auth/login', [
            'email' => $this->customer->email,
            'password' => 'sampleTim@123',
        ]);

        expect($response->status())->toBe(200);
    });

    test('Customer can complete profile', function () {
        //    Sanctum::actingAs(
        //        $customer,
        //        Customer::OWNER_ABILITIES
        //    );

        $response = $this->actingAs($this->customer)->post('/api/v1/auth/complete-profile', [
            'first_name' => fake()->firstName,
            'last_name' => fake()->lastName,
            'phone_number' => '08103797739',
            'business_name' => fake()->company,
            'state_id' => 2671,
            'password' => 'PallEord@123',
            'password_confirmation' => 'PallEord@123',
        ]);

        expect($response->status())->toBe(200);
    });

    test('Customer can resend otp', function () {

        //only resend otp for emails that have not been verified
        $this->customer = Customer::factory()->create([
            'otp_expires_at' => now()->addMinutes(15),
            'email_verified_at' => null,
        ]);

        $response = $this->post('/api/v1/auth/resend-otp', [
            'email' => $this->customer->email,
        ]);

        expect($response->status())->toBe(200);
    });

    test('Verify Customer Email Using Otp', function () {

        $this->customer = Customer::factory()->create([
            'otp_expires_at' => now()->addMinutes(15),
        ]);

        $response = $this->post('/api/v1/auth/verify-email', [
            'email' => $this->customer->email,
            'otp' => $this->customer->otp,
        ]);

        expect($response->status())->toBe(200);
    });

    test('Customers can request a opt for new account', function () {

        $response = $this->post('/api/v1/auth/register', [
            'email' => 'crayolu@gmail.com',
        ]);

        expect($response->status())->toBe(200);
    });

    test('users can logout', function () {
        $user = Customer::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertNoContent();
    });
});

//account id, balance_before, balance_after,account_locked,account_locked_session,restricted,restricted_reason,created_at, updated_at

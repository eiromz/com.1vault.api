<?php

use App\Models\AuthUser as User;
use App\Models\Customer;
use Database\Seeders\DatabaseSeeder;
use Laravel\Sanctum\Sanctum;

test('Customers can logout', function () {
    $this->seed(DatabaseSeeder::class);

    $customer = Customer::factory()->create([
        'phone_number' => '08103797739',
        'password' => Hash::make('sampleTim@123')
    ]);

    Sanctum::actingAs(
        $customer,
        Customer::OWNER_ABILITIES
    );

    $response = $this->post('/api/v1/auth/logout');

    $response->dump();

    expect($response->status())->toBe(200);
});

test('Customers can login', function () {
    $customer = Customer::factory()->create([
        'password' => Hash::make('sampleTim@123')
    ]);

    $response = $this->post('/api/v1/auth/login', [
        'email'     => $customer->email,
        'password'  => 'sampleTim@123',
    ]);

    $response->dump();

    expect($response->status())->toBe(200);
});

test('Customer can complete profile', function () {

    $this->seed(DatabaseSeeder::class);

    $customer = Customer::factory()->create([
        'phone_number' => '08103797739'
    ]);

    Sanctum::actingAs(
        $customer,
        Customer::OWNER_ABILITIES
    );

    $response = $this->post('/api/v1/auth/complete-profile',[
        'first_name'                => fake()->firstName,
        'last_name'                 => fake()->lastName,
        'phone_number'              => "08103797739",
        'business_name'             => fake()->company,
        'state_id'                  => 2671,
        'password'                  => 'PallEord@123',
        'password_confirmation'     => 'PallEord@123'
    ]);

    $response->dump();

    expect($response->status())->toBe(200);
});

test('Customer can resend otp', function () {

    //only resend otp for emails that have not been verified
    $customer = Customer::factory()->create([
        'otp_expires_at'     => now()->addMinutes(15),
        'email_verified_at'  => null,
    ]);

    $response = $this->post('/api/v1/auth/resend-otp', [
        'email' => $customer->email
    ]);

    expect($response->status())->toBe(200);
});

test('Verify Customer Email', function () {

    $customer = Customer::factory()->create([
        'otp_expires_at' => now()->addMinutes(15)
    ]);

    $response = $this->post('/api/v1/auth/verify-email', [
        'email' => $customer->email,
        'otp'   => $customer->otp,
    ]);

    expect($response->status())->toBe(200);
});

test('Customers can setup a new account', function () {

    $response = $this->post('/api/v1/auth/register', [
        'email' => 'crayolu@gmail.com'
    ]);

    expect($response->status())->toBe(200);
});

test('users can logout', function () {
    $user = Customer::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $this->assertGuest();
    $response->assertNoContent();
});

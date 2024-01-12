<?php

use App\Models\Customer;
use App\Models\Profile;
use App\Models\State;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class)->in('Feature');

describe('Auth Routes', function () {
    beforeEach(function () {
        $this->seed(DatabaseSeeder::class);

        $this->phone_number = '081037977'.fake()->randomNumber(2, true);
        $this->state = State::query()
            ->where('country_id', '=', 160)
            ->where('name', '=', 'Lagos')->first();

        $this->customer = Customer::factory()->create([
            'password' => Hash::make('sampleTim@123'),
            'phone_number' => $this->phone_number,
            'otp_expires_at' => now()->addMinutes(15),
            'email' => 'crayoluauth@gmail.com',
        ]);

        $this->profile = Profile::factory()->create([
            'customer_id' => $this->customer->id,
            'firstname' => 'Babatunde',
        ]);

    });

    test('Customers verify otp', function () {
        $response = $this->post('/api/v1/auth/verify-otp', [
            'email' => $this->customer->email,
            'otp' => $this->customer->otp,
        ]);

        expect($response->status())->toBe(200);
    });

    test('Customers reset password using otp', function () {

        $response = $this->post('/api/v1/auth/reset-password', [
            'email' => $this->customer->email,
            'password' => 'sampleTim@123',
            'password_confirmation' => 'sampleTim@123',
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
        $response = $this->actingAs($this->customer)->post('/api/v1/auth/complete-profile', [
            'first_name' => fake()->firstName,
            'last_name' => fake()->lastName,
            'phone_number' => '081037977'.fake()->randomNumber(2, true),
            'business_name' => fake()->company,
            'state_id' => $this->state->id,
            'password' => 'PallEord@123',
            'password_confirmation' => 'PallEord@123',
        ]);
        $response->dump();

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

        $response->dump();

        expect($response->status())->toBe(200);
    });

    test('Customers can request a opt for new account', function () {

        $response = $this->post('/api/v1/auth/register', [
            'email' => 'seun@mailinator.com',
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

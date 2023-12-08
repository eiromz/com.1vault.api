<?php

use App\Models\AuthUser as User;
use App\Models\Customer;

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

test('users can not authenticate with invalid password', function () {
    $user = Customer::factory()->create();

    $this->post('/a', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('users can logout', function () {
    $user = Customer::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $this->assertGuest();
    $response->assertNoContent();
});

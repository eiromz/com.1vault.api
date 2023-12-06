<?php

use App\Models\AuthUser as User;
use App\Models\Customer;

//test if models can be populated for auth_user
//test if models can be populated for wallet
//test if models can be populated for subscription.

test('users can setup a new account', function () {

    $response = $this->post('/api/v1/auth/register', [
        'email' => 'crayolugmail.com'
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

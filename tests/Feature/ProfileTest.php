<?php

use App\Models\Business;
use App\Models\Customer;
use App\Models\State;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class)->in('Feature');

describe('Profile Routes', function () {
    beforeEach(function () {
        $this->seed(DatabaseSeeder::class);

        $this->customer = Customer::where('email', 'crayolu@gmail.com')->with('profile')->first();

        $this->customerWithOutProfile = Customer::factory()->create([
            'email' => 'crayoluman@gmail.com',
        ]);

        $this->state = State::query()
            ->where('name', '=', 'Lagos')
            ->first();

        $this->store_front = Business::factory()->create([
            'state_id' => $this->state->id,
            'customer_id' => $this->customer->id,
            'is_store_front' => true,
        ]);
    });
    test('Merchant can submit kyc information', function () {
        $response = $this->actingAs($this->customer)->post('/api/v1/profile/kyc', [
            'bvn' => '12345678090',
            'doc_type' => 'drivers_license',
            'doc_image' => 'https://1vault-staging-1.fra1.cdn.digitaloceanspaces.com/1vault-staging-1/docs/BmUjTlOlLW8dKpTaTGg5UV97yci2UetoPKqA7iYn.jpg',
            'selfie' => 'https://1vault-staging-1.fra1.cdn.digitaloceanspaces.com/1vault-staging-1/docs/BmUjTlOlLW8dKpTaTGg5UV97yci2UetoPKqA7iYn.jpg',
            'utility_bill' => 'https://1vault-staging-1.fra1.cdn.digitaloceanspaces.com/1vault-staging-1/docs/BmUjTlOlLW8dKpTaTGg5UV97yci2UetoPKqA7iYn.jpg',
        ]);
        $response->dump();
        expect($response->status())->toBe(200);
    });
    test('Merchant can fetch doc types', function () {
        $response = $this->actingAs($this->customer)->get('/api/v1/doc-types');
        $response->dump();
        expect($response->status())->toBe(200);
    });
    test('Customers can forgot their pin', function () {
        $response = $this->actingAs($this->customer)->post('/api/v1/profile/transaction-pin', [
            'type' => 'forgot',
            'password' => 'sampleTim@123',
            'pin' => '123455',
            'pin_confirmation' => '123455',
        ]);
        $response->dump();
        expect($response->status())->toBe(200);
    });
    test('Customers can create a transaction pin', function () {
        $response = $this->actingAs($this->customer)->post('/api/v1/profile/transaction-pin', [
            'type' => 'create',
            'pin' => '123455',
            'pin_confirmation' => '123455',
        ]);

        $response->dump();
        expect($response->status())->toBe(200);
    });
    test('Customers can change their transaction pin', function () {
        $response = $this->actingAs($this->customer)->post('/api/v1/profile/transaction-pin', [
            'type' => 'change',
            'current_pin' => '123456',
            'pin' => '123455',
            'pin_confirmation' => '123455',
        ]);
        $response->dump();
        expect($response->status())->toBe(200);
    });
    test('Customers can change their password when logged in', function () {
        $response = $this->actingAs($this->customer)->post('/api/v1/profile/change-password', [
            'current_password' => 'sampleTim@123',
            'password' => 'sampleTim@1234',
            'password_confirmation' => 'sampleTim@1234',
        ]);
        $response->dump();
        expect($response->status())->toBe(200);
    });
    test('Customers can update their profile', function () {
        $response = $this->actingAs($this->customer)->post('/api/v1/profile', [
            'firstname' => fake()->firstName,
            'email' => 'jola@mailinator.com',
            'lastname' => fake()->lastName,
            'phone_number' => '08139691937',
            'firebase_token' => 'wekjnskdjnkfndfknjsdf',
            'business_name' => 'Olubekunaa',
            'business_physical_address' => 'Olubekunooo',
            'business_zip_code' => 'Olubekun',
            'business_logo' => 'Olubekun',
        ]);
        $response->dump();
        expect($response->status())->toBe(200);
    });
    test('Customers can view their profile', function () {
        $response = $this->actingAs($this->customer)->get('/api/v1/profile');
        $response->dump();
        expect($response->status())->toBe(200);
    });
    test('Customers can delete account', function () {
        $response = $this->actingAs($this->customer)->post('/api/v1/profile/delete-account');

        expect($response->status())->toBe(200);
    });
});

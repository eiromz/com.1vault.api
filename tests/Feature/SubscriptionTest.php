<?php

use App\Models\Customer;
use App\Models\Subscription;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class)->in('Feature');

describe('Subscription Routes', function () {
    beforeEach(function () {
        $this->seed(DatabaseSeeder::class);

        $this->customer = Customer::query()->where('email', '=','crayolu@gmail.com')
            ->with('profile')->first();

        $this->subscription = Subscription::query()
            ->where('customer_id','=',$this->customer->id)
            ->first();

        $this->customerWithOutProfile = Customer::factory()->create([
            'email' => 'crayoluman@gmail.com',
        ]);
    });
    test('Merchant can view all subscriptions', function () {
        $response = $this->actingAs($this->customer)->get('/api/v1/subscriptions');
        $response->dump();
        expect($response->status())->toBe(200);
    });
    test('Merchant can view a subscriptions', function () {
        $response = $this->actingAs($this->customer)->get('/api/v1/subscriptions/'.$this->subscription->id);
        $response->dump();
        expect($response->status())->toBe(200);
    });
    test('Merchant can identify active subscriptions', function (){
        $response = $this->actingAs($this->customer)->get('/api/v1/active/subscriptions');
        expect($response->status())->toBe(404);
    });
});

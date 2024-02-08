<?php

use App\Models\Business;
use App\Models\Client;
use App\Models\Customer;
use App\Models\Inventory;
use App\Models\Invoice;
use App\Models\Receipt;
use App\Models\State;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class)->in('Feature');

describe('Business Routes', function () {
    beforeEach(function () {
        $this->seed(DatabaseSeeder::class);

        $this->state = State::query()
            ->where('country_id', '=', 160)
            ->where('name', '=', 'Lagos')->first();

        $this->customer = Customer::where('email', '=', 'crayolu@gmail.com')->with('profile')->first();

        $this->business = Business::factory()->create([
            'state_id' => $this->state->id,
            'customer_id' => $this->customer->id,
            'is_store_front' => false
        ]);

        $this->client = Client::factory()->create([
            'business_id' => $this->business->id,
            'customer_id' => $this->customer->id,
            'fullname' => 'Apostle Atokolos',
        ]);

        $this->invoice = Invoice::factory()->count(3)->create([
            'business_id' => $this->business->id,
            'customer_id' => $this->customer->id,
            'client_id' => $this->client->id,
            'items' => [
                [
                    'inventory_id' => fake()->uuid,
                    'name' => 'Hackett',
                    'amount' => 'Stark',
                    'unit' => 'Johnston',
                    'quantity' => 3,
                ],
                [
                    'inventory_id' => fake()->uuid,
                    'name' => 'Hackett',
                    'amount' => 'Stark',
                    'unit' => 'Johnston',
                    'quantity' => 3,
                ],
            ],
        ]);

        $this->receipt = Receipt::factory()->count(3)->create([
            'business_id' => $this->business->id,
            'customer_id' => $this->customer->id,
            'client_id' => $this->client->id,
            'items' => [
                [
                    'inventory_id' => fake()->uuid,
                    'name' => 'Hackett',
                    'amount' => 'Stark',
                    'unit' => 'Johnston',
                    'quantity' => 3,
                ],
                [
                    'inventory_id' => fake()->uuid,
                    'name' => 'Hackett',
                    'amount' => 'Stark',
                    'unit' => 'Johnston',
                    'quantity' => 3,
                ],
            ],
        ]);

        $this->inventory = Inventory::factory()->count(3)->create([
            'business_id' => $this->business->id,
            'customer_id' => $this->customer->id,
        ]);

        $this->journal = \App\Models\Journal::latest()->first();
    });

    /*********** Staff ****************/
    test('Merchant can create a staff', function () {
        $response = $this->actingAs($this->customer)->post('/api/v1/staff', [
            'firstname' => 'Sam',
            'lastname' => 'Loco Efe',
            'email' => 'crayolubiz@gmail.com',
            'password' => 'sampleTim@123',
            'password_confirmation' => 'sampleTim@123',
        ]);
        $response->dump();
        expect($response->status())->toBe(200);
    });

    test('Merchant can list all staffs', function() {
        $response = $this->actingAs($this->customer)->get('/api/v1/staff');
        $response->dump();
        expect($response->status())->toBe(200);
    });
});

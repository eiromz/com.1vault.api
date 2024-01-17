<?php

use App\Models\Business;
use App\Models\Client;
use App\Models\Customer;
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

        $this->customer = Customer::where('email','=','crayolu@gmail.com')->with('profile')->first();

        $this->business = Business::factory()->create([
            'state_id' => $this->state->id,
            'customer_id' => $this->customer->id
        ]);

        $this->client = Client::factory()->create([
            'business_id' => $this->business->id,
            'customer_id' => $this->customer->id,
            'fullname'    => 'Apostle Atokolos'
        ]);
    });
    test('Customer can create a business', function () {
        $response = $this->actingAs($this->customer)->post('/api/v1/business', [
            'name'          => '12345678090',
            'phone_number'  => '08103797739',
            'email'         => 'crayolubiz@gmail.com',
            'address'       => 'https://1vault-staging-1.fra1.cdn.digitaloceanspaces.com/1vault-staging-1/docs/BmUjTlOlLW8dKpTaTGg5UV97yci2UetoPKqA7iYn.jpg',
            'state_id'      => $this->state->id,
            'zip_code'      => '1001261',
            'logo'          => 'https://1vault-staging-1.fra1.cdn.digitaloceanspaces.com/1vault-staging-1/docs/BmUjTlOlLW8dKpTaTGg5UV97yci2UetoPKqA7iYn.jpg',
        ]);
        $response->dump();
        expect($response->status())->toBe(200);
    });
    test('Customer can view a business', function () {
        $response = $this->actingAs($this->customer)->post('/api/v1/business/view', [
            'business_id' => $this->business->id,
        ]);
        $response->dump();
        expect($response->status())->toBe(200);
    });
    test('Customer can view all business', function () {
        $response = $this->actingAs($this->customer)->get('/api/v1/business');
        $response->dump();
        expect($response->status())->toBe(200);
    });
    test('Business can create client for invoice', function () {
        $response = $this->actingAs($this->customer)->post('/api/v1/client', [
            'name'          => 'Maxwell Camelo',
            'phone_number'  => '0810379'.fake()->randomNumber(4, true),
            'address'       => 'https://1vault-staging-1.fra1.cdn.digitaloceanspaces.com/1vault-staging-1/docs/BmUjTlOlLW8dKpTaTGg5UV97yci2UetoPKqA7iYn.jpg',
            'business_id'   => $this->business->id,
            'state_id'      => $this->state->id,
            'zip_code'    => '1001261',
        ]);
        $response->dump();
        expect($response->status())->toBe(200);
    });
    test('Business can view client', function () {
        $response = $this->actingAs($this->customer)->post('/api/v1/client/view', [
            'client_id'  => $this->client->id,
        ]);
        $response->dump();
        expect($response->status())->toBe(200);
    });
    test('Business can view all client', function () {
        $link = '/api/v1/client/'.$this->business->id.'/business';
        $response = $this->actingAs($this->customer)->get($link);
        $response->dump();
        expect($response->status())->toBe(200);
    });
    test('Business can create invoice', function(){
        $response = $this->actingAs($this->customer)->post('/api/v1/invoice', [
            'client_id'  => $this->client->id,
            'items'      => [
                [
                    'name' => fake()->lastName,
                    'amount' => fake()->lastName,
                    'unit' => fake()->lastName,
                    'quantity' => 3
                ]
            ],
            'note'              => 'welcome',
            'amount_received'   => 50000,
            'payment_method'    => 'cash',
            'discount'          =>  1000,
            'tax'               => 500,
            'shipping_fee'      => 400,
            'invoice_date'      => '2024-01-16',
            'due_date'          => '2024-02-06',
        ]);
        $response->dump();
        expect($response->status())->toBe(200);
    });
});

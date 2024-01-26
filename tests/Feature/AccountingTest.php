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
        ]);

        $this->receipt = Receipt::factory()->count(3)->create([
            'business_id' => $this->business->id,
            'customer_id' => $this->customer->id,
            'client_id' => $this->client->id,
        ]);

        $this->inventory = Inventory::factory()->count(3)->create([
            'business_id' => $this->business->id,
            'customer_id' => $this->customer->id,
        ]);
    });

    /*************Business******************/
    test('Customer can create a business', function () {
        $response = $this->actingAs($this->customer)->post('/api/v1/business', [
            'name' => '12345678090',
            'phone_number' => '08103797739',
            'email' => 'crayolubiz@gmail.com',
            'address' => 'https://1vault-staging-1.fra1.cdn.digitaloceanspaces.com/1vault-staging-1/docs/BmUjTlOlLW8dKpTaTGg5UV97yci2UetoPKqA7iYn.jpg',
            'state_id' => $this->state->id,
            'zip_code' => '1001261',
            'logo' => 'https://1vault-staging-1.fra1.cdn.digitaloceanspaces.com/1vault-staging-1/docs/BmUjTlOlLW8dKpTaTGg5UV97yci2UetoPKqA7iYn.jpg',
        ]);
        $response->dump();
        expect($response->status())->toBe(200);
    });
    test('Customer can view a business', function () {
        $response = $this->actingAs($this->customer)->post('/api/v1/business/view', [
            'business' => $this->business->id,
        ]);
        $response->dump();
        expect($response->status())->toBe(200);
    });
    test('Customer can view all business', function () {
        $response = $this->actingAs($this->customer)->get('/api/v1/business');
        $response->dump();
        expect($response->status())->toBe(200);
    });
    test('Customer can edit a business', function () {
        $link = '/api/v1/business/update/'.$this->business->id;
        $response = $this->actingAs($this->customer)->post($link, [
            'name' => 'The company',
            'phone_number' => '0810379'.fake()->randomNumber(4, true),
        ]);
        $response->dump();
        expect($response->status())->toBe(200);
    });
    test('Customer can delete a business', function () {
        $response = $this->actingAs($this->customer)->post('/api/v1/business/delete', [
            'business' => $this->business->id,
        ]);
        $response->dump();
        expect($response->status())->toBe(200);
    });

    /*************Customer******************/
    test('Business can create client for invoice', function () {
        $response = $this->actingAs($this->customer)->post('/api/v1/client', [
            'name' => 'Maxwell Camelo',
            'phone_number' => '0810379'.fake()->randomNumber(4, true),
            'address' => 'https://1vault-staging-1.fra1.cdn.digitaloceanspaces.com/1vault-staging-1/docs/BmUjTlOlLW8dKpTaTGg5UV97yci2UetoPKqA7iYn.jpg',
            'business_id' => $this->business->id,
            'state_id' => $this->state->id,
            'zip_code' => '1001261',
        ]);
        $response->dump();
        expect($response->status())->toBe(200);
    });
    test('Business can view a client', function () {
        $response = $this->actingAs($this->customer)->post('/api/v1/client/view', [
            'client_id' => $this->client->id,
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
    test('Business can edit a client', function () {
        $link = '/api/v1/client/update/'.$this->client->id;
        $response = $this->actingAs($this->customer)->post($link, [
            'name' => 'Maxwell Camelo',
            'phone_number' => '0810379'.fake()->randomNumber(4, true),
        ]);
        $response->dump();
        expect($response->status())->toBe(200);
    });
    test('Business can delete a client', function () {
        $response = $this->actingAs($this->customer)->post('/api/v1/client/delete', [
            'customer' => $this->client->id,
        ]);
        $response->dump();
        expect($response->status())->toBe(200);
    });

    /*************Invoice******************/
    test('Business can create invoice', function () {
        $response = $this->actingAs($this->customer)->post('/api/v1/invoice', [
            'client' => $this->client->id,
            'business' => $this->business->id,
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
            'note' => 'welcome',
            'amount_received' => 50000,
            'payment_method' => 'cash',
            'discount' => 1000,
            'tax' => 500,
            'shipping_fee' => 400,
            'total' => 50000,
            'invoice_date' => now()->addDays(2)->format('Y-m-d'),
            'due_date' => now()->addDays(30)->format('Y-m-d'),
        ]);
        $response->dump();
        expect($response->status())->toBe(200);
    });
    test('Business can delete invoice', function () {
        $response = $this->actingAs($this->customer)->post('/api/v1/invoice/delete', [
            'invoice' => $this->invoice->first()->id,
        ]);
        $response->dump();
        expect($response->status())->toBe(200);
    });
    test('Business can edit invoice', function () {
        $response = $this->actingAs($this->customer)
            ->post('/api/v1/invoice/edit/'.$this->invoice->first()->id, [
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
                'payment_status' => 1,
                'note' => 'welcome',
                'amount_received' => 50000,
                'payment_method' => 'cash',
                'discount' => 1000,
                'tax' => 500,
                'shipping_fee' => 400,
                'total' => 50000,
                'invoice_date' => now()->addDays(2)->format('Y-m-d'),
                'due_date' => now()->addDays(30)->format('Y-m-d'),
            ]);
        $response->dump();
        expect($response->status())->toBe(200);
    });
    test('Business can view an invoice', function () {
        $link = '/api/v1/invoice/'.$this->invoice->first()->id.'/business/'.$this->business->id;
        $response = $this->actingAs($this->customer)->get($link);
        $response->dump();
        expect($response->status())->toBe(200);
    });
    test('Business can view all invoice', function () {
        $link = '/api/v1/invoice/business/'.$this->business->id;
        $response = $this->actingAs($this->customer)->get($link);
        $response->dump();
        expect($response->status())->toBe(200);
    });

    /*************Inventory ******************/
    test('Business can Create Inventory', function () {
        $response = $this->actingAs($this->customer)->post('/api/v1/inventory', [
            'name' => fake()->lastName,
            'amount' => fake()->numberBetween(100, 1000),
            'selling_price' => fake()->numberBetween(100, 1000),
            'quantity' => fake()->numberBetween(100, 1000),
            'unit' => fake()->numberBetween(100, 1000),
            'business' => $this->business->id,
        ]);
        $response->dump();
        expect($response->status())->toBe(200);
    });
    test('Business can view all Inventories', function () {
        $response = $this->actingAs($this->customer)->get('/api/v1/inventory/business/'.$this->business->id);
        $response->dump();
        expect($response->status())->toBe(200);
    });
    test('Business can edit Inventory', function () {
        $link = '/api/v1/inventory/edit/'.$this->inventory->first()->id;
        $response = $this->actingAs($this->customer)->post($link, [
            'name' => 'ola Damilola Update',
            'amount' => fake()->numberBetween(100, 1000),
            'selling_price' => fake()->numberBetween(100, 1000),
            'quantity' => fake()->numberBetween(100, 1000),
            'unit' => fake()->numberBetween(100, 1000),
        ]);

        $response->dump();
        expect($response->status())->toBe(200);
    });
    test('Business can view Inventory', function () {
        $link = '/api/v1/inventory/'.$this->inventory->first()->id.'/business/'.$this->business->id;
        $response = $this->actingAs($this->customer)->get($link);
        $response->dump();
        expect($response->status())->toBe(200);
    });
    test('business can delete Inventory', function () {
        $response = $this->actingAs($this->customer)->post('/api/v1/inventory/delete', [
            'inventory' => [
                [
                    'inventory' => $this->inventory->first()->id,
                ],
                [
                    'inventory' => fake()->uuid,
                ],
            ],
        ]);
        $response->dump();
        expect($response->status())->toBe(200);
    });

    /*************Receipt ******************/
    test('Business can delete Receipt', function () {
        $response = $this->actingAs($this->customer)->post('/api/v1/receipt/delete', [
            'receipt' => $this->receipt->first()->id,
        ]);
        $response->dump();
        expect($response->status())->toBe(200);
    });
    test('Business can Create Receipt', function () {
        $response = $this->actingAs($this->customer)->post('/api/v1/receipt', [
            'client' => $this->client->id,
            'business' => $this->business->id,
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
            'description' => 'welcome',
            'amount_received' => 50000,
            'payment_method' => 'cash',
            'discount' => 1000,
            'tax' => 500,
            'total' => 50000,
            'transaction_date' => now()->addDays(2)->format('Y-m-d'),
        ]);
        $response->dump();
        expect($response->status())->toBe(200);
    });
    test('Business can view all Receipts', function () {
        $response = $this->actingAs($this->customer)->get('/api/v1/receipt/business/'.$this->business->id);
        $response->dump();
        expect($response->status())->toBe(200);
    });
    test('Business can edit Receipt', function () {
        $link = '/api/v1/receipt/edit/'.$this->receipt->first()->id;
        $response = $this->actingAs($this->customer)->post($link, [
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
            'description' => 'welcome',
            'amount_received' => 50000,
            'payment_method' => 'cash',
            'discount' => 1000,
            'tax' => 500,
            'total' => 50000,
            'transaction_date' => now()->addDays(2)->format('Y-m-d'),
        ]);

        $response->dump();
        expect($response->status())->toBe(200);
    });
    test('Business can view Receipt', function () {
        $link = '/api/v1/receipt/'.$this->receipt->first()->id.'/business/'.$this->business->id;
        $response = $this->actingAs($this->customer)->get($link);
        $response->dump();
        expect($response->status())->toBe(200);
    });

    /*************Report ******************/
    test('Business can Retrieve Report', function () {
        $response = $this->actingAs($this->customer)->post('/download/pdf', [
            'start_date' => '2024-01-01',
            'end_date' => '2024-02-10',
            'business' => $this->business->id,
            'type' => 'debtors',
        ]);
        $response->dump();
        expect($response->status())->toBe(200);
    });
});

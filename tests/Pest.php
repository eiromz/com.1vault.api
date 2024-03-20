<?php

use App\Models\PosRequest;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Business;
use App\Models\Client;
use App\Models\Customer;
use App\Models\Inventory;
use App\Models\Invoice;
use App\Models\Journal;
use App\Models\Receipt;
use App\Models\State;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

uses(TestCase::class, RefreshDatabase::class)->beforeEach(function () {
    $this->seed(DatabaseSeeder::class);

    $state = State::query()->where('country_id', '=', 160)
        ->where('name', '=', 'Lagos')->first();

    $customer = Customer::where('email','=','crayolu@gmail.com')->with('profile')->first();

    $business = Business::factory()->create([
        'state_id' => $state->id,
        'customer_id' => $customer->id,
        'is_store_front' => false,
    ]);

    $client = Client::factory()->create([
        'business_id' => $business->id,
        'customer_id' => $customer->id,
        'fullname' => 'Apostle Atokolos',
    ]);

    Invoice::factory()->count(3)->create([
        'business_id' => $business->id,
        'customer_id' => $customer->id,
        'client_id' => $client->id,
        'items' => [
            [
                'inventory_id' => fake()->uuid,
                'name' => 'Hackett',
                'amount' => 1000,
                'unit' => 'Johnston',
                'quantity' => 3,
            ],
            [
                'inventory_id' => fake()->uuid,
                'name' => 'Hackett',
                'amount' => 2000,
                'unit' => 'Johnston',
                'quantity' => 3,
            ],
        ],
    ]);

    Receipt::factory()->count(3)->create([
        'business_id' => $business->id,
        'customer_id' => $customer->id,
        'client_id' => $client->id,
        'items' => [
            [
                'inventory_id' => fake()->uuid,
                'name' => 'Hackett',
                'amount' => 1000,
                'unit' => 'Johnston',
                'quantity' => 3,
            ],
            [
                'inventory_id' => fake()->uuid,
                'name' => 'Hackett',
                'amount' => 2000,
                'unit' => 'Johnston',
                'quantity' => 3,
            ],
        ],
    ]);

    Inventory::factory()->count(3)->create([
        'business_id' => $business->id,
        'customer_id' => $customer->id,
    ]);

   PosRequest::factory()->create([
        'state_id' => $state->id,
        'customer_id' => $customer->id,
    ]);
})->group('accounting')->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function something()
{
    // ..
}

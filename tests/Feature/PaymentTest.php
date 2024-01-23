<?php
use App\Models\Customer;
use App\Models\State;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class)->in('Feature');

describe('Payment Routes', function () {
    beforeEach(function () {
        $this->seed(DatabaseSeeder::class);

        $this->state = State::query()
            ->where('country_id', '=', 160)
            ->where('name', '=', 'Lagos')->first();

        $this->customer = Customer::where('email', '=', 'crayolu@gmail.com')->with('profile')->first();
    });

    /*************Report ******************/
    test('Customer can perform name search', function () {
        $response = $this->actingAs($this->customer)->post('/api/v1/wallets/name-search', [
            'account_number' => $this->customer->profile->account_number,
        ]);
        $response->dump();
        expect($response->status())->toBe(200);
    });


});

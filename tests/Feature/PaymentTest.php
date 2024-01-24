<?php
use App\Models\Customer;
use App\Models\Journal;
use App\Models\Profile;
use App\Models\Service;
use App\Models\ServiceBenefit;
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

        $this->customer         = Customer::where('email', '=', 'crayolu@gmail.com')->with('profile')->first();
        $this->service          = Service::factory()->count(3)->create();
        $this->service_benefit  = ServiceBenefit::factory()->count(3)->create([
            'service_id' => $this->service->first()->id
        ]);
        $this->journal = Journal::factory()->count(3)->create([
            'customer_id' => $this->customer->id,
        ]);
    });

    /*************Report ******************/
    test('Customer can perform name search', function () {
        $response = $this->actingAs($this->customer)->post('/api/v1/wallets/name-search', [
            'account_number' => $this->customer2->profile->account_number,
        ]);
        $response->dump();
        expect($response->status())->toBe(200);
    });

    test('Customer can fetch all journal transactions', function(){
        $response = $this->actingAs($this->customer)->post('/api/v1/wallets/journal',[
            'filter_type' => 'default',
            'start_date'    => '2024-01-24',
            'end_date'      => '2024-01-27',
        ]);
        $response->dump();
        expect($response->status())->toBe(200);
    });

    test('Customer can fetch a single journal transaction', function() {
        $response = $this->actingAs($this->customer)->post('/api/v1/wallets/journal/view',[
            'trx_ref' => $this->journal->first()->trx_ref,
        ]);
        $response->dump();
        expect($response->status())->toBe(200);
    });

    test('Customer can perform transaction transfers', function(){
        $customer2 = Customer::factory()->create([
            'email' => 'crayolu2@gmail.com'
        ]);

        Profile::factory()->create([
            'customer_id' => $customer2->id,
            'account_number' => '0417083628',
            'state_id' => $this->state->id,
        ]);

        Journal::factory()->create([
            'customer_id' => $customer2->id
        ]);

        $response = $this->actingAs($this->customer)->post('/api/v1/wallets/journal/transfer',[
            'account_number' => $customer2->profile->account_number,
            'amount'         => 1000000000000
        ]);

        $response->dump();

        expect($response->status())->toBe(200);
    });
});

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
    test('Customer can view services by category', function () {
        $response = $this->actingAs($this->customer)->post('/api/v1/service', [
            'category' => $this->service->first()->category,
        ]);
        $response->dump();
        expect($response->status())->toBe(200);
    });
    test('Customer can fill service request',function(){

    });
});

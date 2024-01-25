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
    test('Customer can fill service request for business_name',function(){
        $response = $this->actingAs($this->customer)->post('/api/v1/service/create-request', [
            'type' => 'business_name',
            'business_name'  => ['bookworm','title'],
            'nature_of_business'  => 'bookworm',
            'government_id_pdf' => fake()->url,
            'email_address' => fake()->email,
            'phone_number' => fake()->phoneNumber,
            'physical_address' => fake()->address,
            'email_address_proprietors' => fake()->email,
            'email_address_directors' => fake()->email,
            'phone_number_proprietors' => fake()->phoneNumber,
            'phone_number_directors' => fake()->phoneNumber,
            'physical_address_of_directors' => fake()->address,
            'name_of_directors' => fake()->lastName,
            'signature_of_proprietors_pdf' => fake()->url,
            'signature_of_directors_pdf' => fake()->url,
            'passport_photograph_of_directors_pdf' => fake()->url,
            'utility_bill_pdf' => fake()->url,
            'comments' => fake()->phoneNumber,
        ]);
        $response->dump();
        expect($response->status())->toBe(200);
    });
    test('Customer can fill service request for business llc',function(){
        $response = $this->actingAs($this->customer)->post('/api/v1/service/create-request', [
            'type' => 'business_llc',
            'business_name'  => ['bookworm','title'],
            'nature_of_business'  => 'bookworm',
            'government_id_pdf' => fake()->url,
            'email_address' => fake()->email,
            'phone_number' => fake()->phoneNumber,
            'physical_address' => fake()->address,
            'email_address_directors' => fake()->email,
            'phone_number_directors' => fake()->phoneNumber,
            'physical_address_of_directors' => fake()->address,
            'name_of_directors' => fake()->lastName,
            'signature_of_directors_pdf' => fake()->url,
            'passport_photograph_of_directors_pdf' => fake()->url,
            'comments' => fake()->phoneNumber,
        ]);
        $response->dump();
        expect($response->status())->toBe(200);
    });
});

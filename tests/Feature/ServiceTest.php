<?php

use App\Models\Customer;
use App\Models\Journal;
use App\Models\Service;
use App\Models\ServiceBenefit;
use App\Models\State;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class)->in('Feature');

describe('Service Routes', function () {
    beforeEach(function () {
        $this->seed(DatabaseSeeder::class);

        $this->state = State::query()
            ->where('country_id', '=', 160)
            ->where('name', '=', 'Lagos')->first();

        $this->customer = Customer::where('email', '=', 'crayolu@gmail.com')->with('profile')->first();
        $this->service = Service::factory()->count(100)->create([
            'discount' => 10,
            'amount' => 100000,
            'has_discount' => 1,
        ]);
        $this->service_benefit = ServiceBenefit::factory()->count(3)->create([
            'service_id' => $this->service->first()->id,
        ]);
        $this->journal = Journal::factory()->count(3)->create([
            'customer_id' => $this->customer->id,
        ]);
    });

    /*************Report ******************/
    test('Merchant can view services by category', function () {
        $response = $this->actingAs($this->customer)->post('/api/v1/service', [
            'category' => $this->service->first()->category,
        ]);
        $response->dump();
        expect($response->status())->toBe(200);
    });
    test('Merchant can fill service request for business_name', function () {
        $response = $this->actingAs($this->customer)->post('/api/v1/service/create-request', [
            'type' => 'business_name',
            'business_name' => ['bookworm', 'title'],
            'nature_of_business' => 'bookworm',
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
    test('Merchant can fill service request for business llc', function () {
        $response = $this->actingAs($this->customer)->post('/api/v1/service/create-request', [
            'type' => 'business_llc',
            'business_name' => ['bookworm', 'title'],
            'nature_of_business' => 'bookworm',
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
    test('Merchant can fill service request for pos', function () {
        $response = $this->actingAs($this->customer)->post('/api/v1/service/create-request', [
            'type' => 'pos',
            'business_name' => 'BusinessNamedaklmdlamkdlmasd',
            'merchant_trade_name' => 'MerchantTraddadasdadasdasdasdasdasdae',
            'business_type' => 'sole_owner',
            'category' => 'restaurant',
            'office_address' => fake()->lastName,
            'local_govt_area' => fake()->lastName,
            'state_id' => $this->state->id,
            'primary_contact_person' => [
                [
                    'name' => fake()->uuid,
                    'designation' => 'Hackett',
                    'office_phone' => 'Stark',
                    'mobile_phone' => 'Stark',
                    'email_address' => 'Stark',
                ],
            ],
            'secondary_contact_person' => [
                [
                    'name' => fake()->uuid,
                    'designation' => 'Hackett',
                    'office_phone' => 'Stark',
                    'mobile_phone' => 'Stark',
                    'email_address' => 'Stark',
                ],
            ],
            'pos_quantity' => 10,
            'pos_locations' => [
                [
                    'location_of_terminal' => fake()->uuid,
                    'contact_person' => 'Hackett',
                    'mobile_phone' => 'Stark',
                ],
                [
                    'location_of_terminal' => fake()->uuid,
                    'contact_person' => 'Hackett',
                    'mobile_phone' => 'Stark',
                ],
            ],
            'receive_notification' => 1,
            'notification_email_address' => fake()->email,
            'notification_phone_number' => fake()->phoneNumber,
            'real_time_transaction_viewing' => fake()->boolean,
            'settlement_account_name' => fake()->lastName,
            'settlement_account_number' => fake()->lastName,
            'settlement_branch' => fake()->lastName,
            'other_information' => fake()->lastName,
            'attestation' => fake()->lastName,
            'card_type' => 'international_card',
            'signature_pdf_link' => fake()->url,
            'designation' => fake()->lastName,
        ]);
        $response->dump();
        expect($response->status())->toBe(200);
    });
    test('Merchant can fill service request for legal', function () {
        $response = $this->actingAs($this->customer)->post('/api/v1/service/create-request', [
            'type' => 'legal',
            'description' => 'Welcome to the land of the living',
        ]);
        $response->dump();
        expect($response->status())->toBe(200);
    });
    test('Merchant can view analytics for platform', function () {
        $response = $this->actingAs($this->customer)->get('/api/v1/business-analytics');
        $response->dump();
        expect($response->status())->toBe(200);
    });
});

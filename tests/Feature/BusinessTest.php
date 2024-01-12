<?php

use App\Models\Customer;
use App\Models\State;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class)->in('Feature');

describe('Business Routes', function () {
    beforeEach(function () {
        $this->seed(DatabaseSeeder::class);

        $this->customer = Customer::where('email', 'crayolu@gmail.com')->with('profile')->first();
        $this->state = State::query()
            ->where('country_id', '=', 160)
            ->where('name', '=', 'Lagos')->first();
    });

    test('Customer can create a business', function () {
        $response = $this->actingAs($this->customer)->post('/api/v1/client', [
            'name'          => '12345678090',
            'phone_number'  => 'drivers_license',
            'email'         => 'crayolusam@gmail.com',
            'address'       => 'https://1vault-staging-1.fra1.cdn.digitaloceanspaces.com/1vault-staging-1/docs/BmUjTlOlLW8dKpTaTGg5UV97yci2UetoPKqA7iYn.jpg',
            'state_id'      => $this->state->id,
            'logo'          => 'https://1vault-staging-1.fra1.cdn.digitaloceanspaces.com/1vault-staging-1/docs/BmUjTlOlLW8dKpTaTGg5UV97yci2UetoPKqA7iYn.jpg'
        ]);
        $response->dump();
        expect($response->status())->toBe(200);
    });
});

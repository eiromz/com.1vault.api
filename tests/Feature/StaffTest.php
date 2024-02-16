<?php

use App\Models\Business;
use App\Models\Client;
use App\Models\Customer;
use App\Models\Inventory;
use App\Models\Invoice;
use App\Models\Profile;
use App\Models\Receipt;
use App\Models\State;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Src\Merchant\App\Enum\Role;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class)->in('Feature');

describe('Business Routes', function () {
    beforeEach(function () {
        $this->seed(DatabaseSeeder::class);

        $this->state = State::query()
            ->where('country_id', '=', 160)
            ->where('name', '=', 'Lagos')->first();

        $this->customer = Customer::where('email', '=', 'crayolu@gmail.com')->with('profile')->first();

        $this->staff = Customer::factory()->create([
            'is_owner' => false,
            'is_member' => true,
            'ACCOUNTID' => $this->customer->ACCOUNTID,
            'role'  => Role::EMPLOYEE->value,
        ]);

        $this->staff_profile =  Profile::factory()->create([
            'customer_id' => $this->staff->id,
            'state_id' => $this->state->id
        ]);
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
    test('Merchant can delete staff', function() {
        $response = $this->actingAs($this->customer)->post('/api/v1/staff/delete/'.$this->staff->id);
        $response->dump();
        expect($response->status())->toBe(200);
    });
    test('Merchant can update staff', function(){
        $response = $this->actingAs($this->customer)->post('/api/v1/staff/edit/'.$this->staff->id,[
            'firstname' => 'SammmMad',
            'lastname' => 'BajadMan',
            'email' => 'crayolubiz@gmail.com',
        ]);
        $response->dump();
        expect($response->status())->toBe(200);
    });
});

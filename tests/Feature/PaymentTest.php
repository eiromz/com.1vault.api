<?php

use App\Models\Cart;
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

        $this->customer = Customer::where('email', '=', 'crayolu@gmail.com')->with('profile')->first();

        $this->service = Service::query()->where('is_recurring','=',true)->first();

        $this->journal = Journal::factory()->count(3)->create([
            'customer_id' => $this->customer->id,
        ]);

        $this->cart = Cart::factory()->count(1)->create([
            'price' => $this->service->amount,
            'customer_id' => $this->customer->id,
            'service_id' => $this->service->id,
            'account_id' => $this->customer->ACCOUNTID,

        ]);
    });

    /*************Report ******************/
    test('Merchant can perform name search', function () {
        $response = $this->actingAs($this->customer)->post('/api/v1/wallets/name-search', [
            'account_number' => '9977581538',
        ]);
        $response->dump();
        expect($response->status())->toBe(200);
    });
    test('Merchant can fetch all journal transactions', function () {
        $response = $this->actingAs($this->customer)->post('/api/v1/wallets/journal', [
            'filter_type' => 'default',
        ]);
        $response->dump();
        expect($response->status())->toBe(200);
    });
    test('Merchant can fetch a single journal transaction', function () {
        $response = $this->actingAs($this->customer)->post('/api/v1/wallets/journal/view', [
            'trx_ref' => $this->journal->first()->trx_ref,
        ]);
        $response->dump();
        expect($response->status())->toBe(200);
    });
    test('Merchant can perform transaction transfers', function () {
        $customer2 = Customer::factory()->create([
            'email' => 'crayolu2@gmail.com',
        ]);

        Profile::factory()->create([
            'customer_id' => $customer2->id,
            'account_number' => '0417083628',
            'state_id' => $this->state->id,
        ]);

        Journal::factory()->create([
            'customer_id' => $customer2->id,
        ]);

        $response = $this->actingAs($this->customer)->post('/api/v1/wallets/journal/transfer', [
            'account_number' => $customer2->profile->account_number,
            'amount' => 10000,
            'transaction_pin' => '123456',
            'remark' => 'this is good',
        ]);

        $response->dump();

        expect($response->status())->toBe(200);
    });
    test('Merchant can add to cart  with service_id', function () {
        $response = $this->actingAs($this->customer)->post('/api/v1/cart', [
            'service_id' => $this->service->first()->id,
            'price' => $this->service->first()->amount,
        ]);
        $response->dump();
        expect($response->status())->toBe(200);
    });
    test('Merchant can view all in cart', function () {
        $response = $this->actingAs($this->customer)->get('/api/v1/cart');
        $response->dump();
        expect($response->status())->toBe(200);
    });
    test('Merchant can delete from cart', function () {
        $response = $this->actingAs($this->customer)->post('/api/v1/cart/delete', [
            'cart_id' => $this->cart->first()->id,
        ]);
        $response->dump();
        expect($response->status())->toBe(200);
    });
    test('Merchant can pay for service', function () {
        $response = $this->actingAs($this->customer)->post('/api/v1/pay-now', [
            'total' => 10000,
            'transaction_pin' => '123456'
        ]);
        expect($response->status())->toBe(200);
    });

    /************* NIP *************/
    test('Merchant can fetch nip banks', function(){
        $response = $this->actingAs($this->customer)->get('/api/v1/providus/nip/banks');
        expect($response->status())->toBe(200);
    });
    test('Merchant can fetch nip account information', function(){
        $response = $this->actingAs($this->customer)->post('/api/v1/providus/nip/enquiry',[
            'accountNumber' => "1018996198",
            'beneficiaryBank' => "110000",
            'userName' => "test",
            'password' => "test",
        ]);
        $response->dump();
        expect($response->status())->toBe(200);
    });
    test('Merchant can transfer through nip', function(){
        $response = $this->actingAs($this->customer)->post('/api/v1/providus/nip/transfer',[
            "beneficiaryAccountName" => "UGBO, CHARLES UMORE",
            "transactionAmount" => "2000.45",
            "currencyCode" => "NGN",
            "narration" => "Testing",
            "beneficiaryAccountNumber"=>"1700313889",
            "beneficiaryBank" => "000013"
        ]);
        $response->dump();
        expect($response->status())->toBe(200);
    });
});

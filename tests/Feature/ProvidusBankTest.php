<?php

use App\Models\Customer;
use App\Models\KnowYourCustomer;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class)->in('Feature');

describe('ProvidusBank Routes', function () {
    beforeEach(function () {
        $this->customer = Customer::query()->where('email', '=', 'crayolu@gmail.com')->with(['profile','knowYourCustomer'])->first();
    });

    test('Customers can reserve a bank account', function () {
        $response = $this->get('/api/v1/reserve-bank-account');
        expect($response->status())->toBe(404);
    });

    test('Customers can receive settlement to their bank account', function () {
        $response = $this->post('/api/v1/providus/webhook', [
            'sessionId' => '0000042103011805345648005069266636442357859508',
            'accountNumber' => '9977581536',
            'tranRemarks' => 'FROM UBA/ CASAFINA CREDIT-EASY LOAN-NIP/SEYI OLUFEMI/CASAFINA CAP/0000042103015656180548005069266636',
            'transactionAmount' => '1',
            'settledAmount' => '1',
            'feeAmount' => '0',
            'vatAmount' => '0',
            'currency' => 'NGN',
            'initiationTranRef' => '',
            'settlementId' => '202210301006807600001432',
            'sourceAccountNumber' => '2093566866',
            'sourceAccountName' => 'CASAFINA CREDIT-EASY LOAN',
            'sourceBankName' => 'UNITED BANK FOR AFRICA',
            'channelId' => '1',
            'tranDateTime' => '2021-03-01 18:06:20.000',
        ]);

        expect($response->status())->toBe(200);
    });

    test("Service class can check if a customer has a valid bvn account", function(){
        $customers = Customer::query()->where('email','crayolu@gmail.com')
            ->whereHas('profile')
            ->whereHas('knowYourCustomer',function(Builder $query){
                $query->where('status', '=', 1);
            })->first();

        dd($customers?->knowYourCustomer);
    });
});

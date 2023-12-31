<?php

use App\Models\{Customer,KnowYourCustomer,Journal,Profile,State,Account};
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class)->in('Feature');

describe('Auth Routes', function () {
    beforeEach(function () {
        $this->seed(DatabaseSeeder::class);

        $this->customer = Customer::query()->where('email','=','crayolu@gmail.com')->first();

    });

    test('Customers can reserve a bank account', function () {
        $response = $this->get('/api/v1/reserve-bank-account');

        expect($response->status())->toBe(200);
    });

    test('Customers can recieve settlement to their bank account', function(){
        $response = $this->post('/api/v1/pr/webhook/notify',[
            "sessionId" => "0000042103011805345648005069266636442357859508",
            "accountNumber" => "9977581536",
            "tranRemarks" => "FROM UBA/ CASAFINA CREDIT-EASY LOAN-NIP/SEYI OLUFEMI/CASAFINA CAP/0000042103015656180548005069266636",
            "transactionAmount" => "1",
            "settledAmount" => "1",
            "feeAmount" => "0",
            "vatAmount" => "0",
            "currency" => "NGN",
            "initiationTranRef" => "",
            "settlementId" => "202210301006807600001432",
            "sourceAccountNumber" => "2093566866",
            "sourceAccountName" => "CASAFINA CREDIT-EASY LOAN",
            "sourceBankName" => "UNITED BANK FOR AFRICA",
            "channelId" => "1",
            "tranDateTime" => "2021-03-01 18:06:20.000"
        ]);

        $response->dump();

        expect($response->status())->toBe(200);
    });
});

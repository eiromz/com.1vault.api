<?php

use App\Models\Customer;
use App\Models\Journal;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Src\Merchant\Domain\Services\GenerateAccountNumber;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class)->in('Feature');

describe('ProvidusBank Routes', function () {
    beforeEach(function () {
        $this->customer = Customer::query()
            ->where('email', '=', 'crayolu@gmail.com')->with(['profile', 'knowYourCustomer','account'])
            ->first();

        $this->customer->profile->account_number = '9977581536';
        $this->customer->profile->save();

        $this->journal = Journal::factory()->count(3)->create([
            'customer_id' => $this->customer->id,
        ]);

        $sample_response = [
            'account_number' => '9636287810',
            'account_name' => 'EE SOLUTIONS LIMTED(Tyshawn R)',
            'bvn' => '22219452436',
            'requestSuccessful' => true,
            'responseMessage' => 'Reserved Account Generated Successfully',
            'responseCode' => '00',
        ];
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

    test('Service class can check if a customer has a valid bvn account', function () {
        $customer = Customer::query()->where('email', 'crayolu@gmail.com')
            ->whereHas('profile')
            ->whereHas('knowYourCustomer', function (Builder $query) {
                $query->where('status', '=', 1);
            })->first();

        $generateAccountService = new GenerateAccountNumber($customer, $customer->profile, $customer->knowYourCustomer);

        if (! $generateAccountService->payload['requestSuccessful'] || $generateAccountService->payload['responseCode'] !== '00') {
            $generateAccountService->notify('You cannot generate an account number');
        }

        $generateAccountService->save();

    });

    test('Webhooks can send messages to the api', function () {
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

    test('Credit customer using webhook data', function () {
        dd($this->customer);
    });
});

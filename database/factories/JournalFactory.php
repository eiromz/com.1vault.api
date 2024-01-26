<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Journal>
 */
class JournalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $payload = [
            'sessionId' => '0000042103011805345648005069266636442357859508',
            'accountNumber' => '9977581536',
            'tranRemarks' => 'FROM UBA/ CASAFINA CREDIT-EASY LOAN-NIP/SEYI OLUFEMI/CASAFINA CAP/0000042103015656180548005069266636',
            'transactionAmount' => '10000000',
            'settledAmount' => '10000000',
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
        ];

        $random = fake()->randomNumber(6);

        return [
            'trx_ref' => '202210301006807600001432'.$random,
            'session_id' => '0000042103011805345648005069266636442357859508',
            'amount' => $payload['transactionAmount'],
            'commission' => 0,
            'debit' => false,
            'credit' => true,
            'balance_before' => 0,
            'balance_after' => $payload['transactionAmount'],
            'label' => 'Transfer',
            'source' => 'Providus',
            'payload' => json_encode($payload),
        ];

    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PosRequest>
 */
class PosRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'business_name' => 'BusinessNamedaklmdlamkdlmasd',
            'merchant_trade_name' => 'MerchantTraddadasdadasdasdasdasdasdae',
            'business_type' => 'sole_owner',
            'category' => 'restaurant',
            'office_address' => fake()->lastName,
            'local_govt_area' => fake()->lastName,
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
        ];
        //        return [
        //            'business_name'         => fake()->name,
        //            'merchant_trade_name'   => fake()->name,
        //            'business_type'         => 'sole_owner',
        //            'category' => 'store/supermarket',
        //            'office_address' => fake()->streetAddress,
        //            'local_govt_area' => fake()->country,
        //            'primary_contact_person' => [],
        //            'secondary_contact_person' => [],
        //            'pos_quantity' => 10,
        //            'pos_locations' => [],
        //            'receive_notification' => true,
        //            'notification_email_address' => fake()->streetAddress,
        //            'notification_phone_number' => fake()->phoneNumber,
        //            'real_time_transaction_viewing' => true,
        //            'settlement_account_name' => fake()->name,
        //            'settlement_account_number' => '11223344556',
        //            'settlement_branch' => fake()->streetAddress,
        //            'other_information' => fake()->text(50),
        //            'attestation' => 'store/supermarket',
        //            'card_type' => 'master_card',
        //            'signature_pdf_link' => fake()->url
        //        ];
    }
}

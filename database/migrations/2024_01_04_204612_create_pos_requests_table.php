<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pos_requests', function (Blueprint $table) {
            $table->uuid('id')->primary()->index();
            $table->string('business_name');
            $table->string('merchant_trade_name');
            $table->string('business_type')
                ->nullable()
                ->comment('sole_owner,partnership,limted_liability_company,public_limited_company');
            $table->string('category');

            $table->string('office_address');
            $table->string('local_govt_area',30);
            $table->foreignId('state_id');

            $table->jsonb('contact_person')->nullable();
            $table->jsonb('designation')->comment('this designation should accept an array of ');
            $table->jsonb('business_telephone')->comment('accepts multiple data as an array')->nullable();
            $table->jsonb('business_mobile_phone_number')->comment('accepts multiple data as an array')->nullable();
            $table->jsonb('email_address')->comment('accepts multiple data as an array')->nullable();


            $table->jsonb('pos_outlet_number_of_terminals')->comment('accepts multiple data as an array')->nullable();
            $table->jsonb('pos_outlet_location_of_terminals')->comment('accepts multiple data as an array')->nullable();
            $table->jsonb('pos_outlet_contact_person')->comment('accepts multiple data as an array')->nullable();
            $table->jsonb('pos_outlet_mobile_phone')->comment('accepts multiple data as an array')->nullable();
            $table->jsonb('pos_outlet_location_of_terminal')->comment('accepts multiple data as an array')->nullable();

            $table->boolean('receive_notification')->default(0);
            $table->string('notification_email_address',40)->nullable();
            $table->string('notification_phone_number',20)->nullable();
            $table->boolean('real_time_transaction_viewing')->default(0);

            $table->string('settlement_account_name',20);
            $table->string('settlement_account_number',20);
            $table->string('settlement_branch',30);

            $table->longText('other_information')->nullable();
            $table->string('attestation',40)->comment('On behalf of');
            $table->string('card_type',20);
            $table->string('signature_link');

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate();
            $table->softDeletesTz();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pos_requests');
    }
};

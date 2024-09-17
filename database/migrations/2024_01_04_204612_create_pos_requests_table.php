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
            $table->foreignUuid('customer_id');
            $table->string('business_name');
            $table->string('merchant_trade_name');
            $table->string('business_type', 50)
                ->nullable()
                ->comment('sole_owner,partnership,limted_liability_company,public_limited_company');
            $table->string('category', 100);

            $table->string('office_address', 150);
            $table->string('local_govt_area', 150);
            $table->foreignId('state_id');

            $table->jsonb('primary_contact_person')->nullable();
            $table->jsonb('secondary_contact_person')->nullable();
            $table->integer('pos_quantity')->comment('this designation should accept integer ');
            $table->jsonb('pos_locations')->nullable();

            $table->boolean('receive_notification')->default(0);
            $table->string('notification_email_address')->nullable();
            $table->string('notification_phone_number')->nullable();
            $table->boolean('real_time_transaction_viewing')->default(0);

            $table->string('settlement_account_name', 150)->nullable();
            $table->string('settlement_account_number', 30)->nullable();
            $table->string('settlement_branch')->nullable();

            $table->longText('other_information')->nullable();
            $table->string('attestation')->comment('On behalf of whom it may concern');
            $table->string('card_type')->nullable();
            $table->string('signature_pdf_link')->nullable();
            $table->string('designation')->nullable();

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

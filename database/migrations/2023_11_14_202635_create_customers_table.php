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
        Schema::create('customers', function (Blueprint $table) {
            $table->uuid('id')->primary()->index();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone_number', 15)->nullable();
            $table->string('password')->nullable();
            $table->string('role')
                ->default('business_owner')
                ->comment('business_owner,employee,admin');
            $table->string('firebase_token')->nullable();
            $table->boolean('accept_terms_conditions')
                ->default(0);
            $table->boolean('is_owner')
                ->default(true)
                ->comment('identify if the account is the owner account');
            $table->boolean('is_member')
                ->default(false)
                ->comment('identify if the account is the employee account');
            $table->tinyInteger('status')
                ->default(1)
                ->comment('pending:0,active:1,suspended:2,blocked:3,contact-admin:4,fraud:5');
            $table->string('ACCOUNTID',12)
                ->unique()
                ->comment('Account id for the owner of the account, it is always unique to avoid issues');
            $table->string('transaction_pin',6)->nullable();
            $table->string('referral_code',7);
            $table->rememberToken();
            $table->string('otp', 6)->nullable();
            $table->timestamp('otp_expires_at')->nullable();
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
        Schema::dropIfExists('customers');
    }
};

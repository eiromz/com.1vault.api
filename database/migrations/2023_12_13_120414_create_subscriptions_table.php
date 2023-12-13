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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->uuid('id')->primary()->index();
            $table->foreignUuid('customer_id');
            $table->foreignUuid('service_id')->nullable();
            $table->string('charge_type')->comment('one-time,recurring')->nullable();
            $table->double('amount');
            $table->string('trx_ref');
            $table->string('source')->comment('wallet');
            $table->timestamp('subscription_date')->nullable();
            $table->timestamp('expiration_date')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate();
            $table->softDeletesTz();
        });
        //subscription id,service_id,[charge_type:one-time,recurring],description,amount,trx_ref,source:wallet,subscription_date,expiration_date
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};

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
        Schema::create('journals', function (Blueprint $table) {
            $table->uuid('id')->primary()->index();
            $table->foreignUuid('service_id')->nullable();
            $table->foreignUuid('customer_id');
            $table->string('trx_ref');
            $table->double('amount')->default(0);
            $table->integer('commission')->comment('commission in percentage');
            $table->boolean('debit')->default(0);
            $table->boolean('credit')->default(0);
            $table->double('balance_before');
            $table->double('balance_after');
            $table->string('label')->nullable();
            $table->string('source')->comment('wallet,card');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate();
            $table->softDeletesTz();
        });

        //journal id,service_id,trx_ref,amount, commission, debit, credit,balance_before,balance_after,label,charge_type:one-time,recurring
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journals');
    }
};

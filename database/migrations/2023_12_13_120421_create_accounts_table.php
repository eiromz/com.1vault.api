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
        Schema::create('accounts', function (Blueprint $table) {
            $table->uuid('id')->primary()->index();
            $table->foreignUuid('customer_id');
            $table->double('balance_before');
            $table->double('balance_after');
            $table->boolean('restrict')->default(0);
            $table->boolean('has_transaction_lock')->default(0);
            $table->timestamp('transaction_lock_time_stamp')->nullable();
            $table->timestamp('prev_transaction_lock_time_stamp')->nullable();
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
        Schema::dropIfExists('accounts');
    }
};

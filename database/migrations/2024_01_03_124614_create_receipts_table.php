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
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('business_id');
            $table->foreignUuid('customer_id')->nullable();
            $table->foreignUuid('client_id')->nullable();
            $table->foreignUuid('collaborator_id')->nullable();
            $table->jsonb('items')->comment('array for items to be stored in the field');
            $table->longText('description')->nullable();
            $table->double('amount_received')->default(0);
            $table->string('payment_method')->comment('This shows what preferred method of payment should be used');
            $table->double('tax')->default(0);
            $table->double('discount')->default(0);
            $table->double('total')->default(0);
            $table->timestamp('transaction_date');
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
        Schema::dropIfExists('receipts');
    }
};

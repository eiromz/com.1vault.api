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
        Schema::create('inventories', function (Blueprint $table) {
            $table->uuid('id')->primary()->index();
            $table->foreignUuid('business_id');
            $table->foreignUuid('customer_id')->nullable();
            $table->foreignUuid('collaborator_id')->nullable();
            $table->string('product_name');
            $table->double('amount')->comment('This is also the amount');
            $table->double('selling_price')->nullable();
            $table->string('description')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('unit')->nullable();
            $table->boolean('stock_status')->default(1)->comment('0 for out of stock and 1 for available');
            $table->boolean('is_store_front')->default(0);
            $table->string('image')->nullable();
            $table->boolean('is_published')->default(0);
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
        Schema::dropIfExists('inventories');
    }
};

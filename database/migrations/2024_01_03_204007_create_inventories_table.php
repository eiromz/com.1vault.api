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
            $table->string('product_name');
            $table->double('product_cost_price');
            $table->double('product_selling_price')->nullable();
            $table->string('product_description')->nullable();
            $table->integer('product_quantity');
            $table->tinyInteger('product_stock_status')->comment('0 for out of stock and 1 for available');
            $table->boolean('product_is_store_front')->default(0);
            $table->string('product_image')->nullable();
            $table->boolean('published')->default(0);
            $table->foreignUuid('customer_id')->nullable();
            $table->foreignUuid('collaborator_id')->nullable();
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

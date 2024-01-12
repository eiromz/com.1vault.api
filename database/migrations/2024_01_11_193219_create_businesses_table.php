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
        Schema::create('businesses', function (Blueprint $table) {
            $table->uuid('id')->primary()->index();
            $table->foreignUuid('customer_id')->nullable();
            $table->foreignUuid('collaborator_id')->nullable();
            $table->string('fullname')->comment('name for business');
            $table->string('phone_number')->comment('phone number for the client');
            $table->string('email')->unique()->comment('email address of the business');
            $table->string('address')->comment('The business physical address')->nullable();
            $table->foreignId('state_id')->comment('provide your state of residence')->nullable();
            $table->string('logo')->comment('logo in string for the business')->nullable();
            $table->string('zip_code')->nullable();
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
        Schema::dropIfExists('businesses');
    }
};

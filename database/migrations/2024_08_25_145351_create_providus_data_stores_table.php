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
        Schema::create('providus_data_stores', function (Blueprint $table) {
            $table->uuid('id')->primary()->index();
            $table->string('session_id')->index();
            $table->string('account_number')->index();
            $table->string('settlement_id')->index();
            $table->jsonb('payload');
            $table->boolean('processed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('providus_data_stores');
    }
};

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
        Schema::create('know_your_customers', function (Blueprint $table) {
            $table->uuid('id')->primary()->index();
            $table->foreignUuid('customer_id');
            $table->string('doc_type');
            $table->string('doc_number')->unique();
            $table->string('doc_img');
            $table->string('selfie')->nullable();
            $table->foreignUuid('approved_by_admin')->nullable();
            $table->tinyInteger('status')
                ->comment('pending:0,active:1,suspended:2,blocked:3,contact-admin:4,fraud:5,failed');
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
        Schema::dropIfExists('know_your_customers');
    }
};

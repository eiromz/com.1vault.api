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
            $table->string('bvn');
            $table->string('doc_type');
            $table->string('doc_image');
            $table->string('selfie');
            $table->foreignUuid('approved_by_admin')->nullable()->comment('Admin id for the approval of the documents');
            $table->tinyInteger('status')->default(0)
                ->comment('pending:0,active:1,suspended:2,blocked:3,contact-admin:4,fraud:5,6:failed');
            $table->string('utility_bill');
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

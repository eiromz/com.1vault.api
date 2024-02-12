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
            $table->foreignUuid('approved_by_admin')->nullable()->comment('Admin id for the approval of the documents');
            $table->string('bvn');
            $table->string('doc_type');
            $table->string('doc_image');
            $table->string('selfie');
            $table->tinyInteger('status')->default(0)
                ->comment('pending:0,approved:1,suspended:2,blocked:3,contact-admin:4,fraud:5,6:failed');
            $table->string('utility_bill');
            $table->string('comments')->comment('Leave comment for user for viewing');
            $table->jsonb('bvn_validation_payload')->comment('payload of bvn validation for the user')->nullable();
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

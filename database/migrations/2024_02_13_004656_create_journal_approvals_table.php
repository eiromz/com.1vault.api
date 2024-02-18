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
        Schema::create('journal_approvals', function (Blueprint $table) {
            $table->uuid('id')->primary()->index();
            $table->foreignUuid('customer_id');
            $table->foreignUuid('admin_id')->nullable();
            $table->double('amount');
            $table->jsonb('source');
            $table->jsonb('destination');
            $table->string('actions')->nullable()->comment('authorize,reversal');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate();
            $table->softDeletesTz();
            //know where the money is going, destination
            //type would help us know where it is supposed to go to either 1vault or bank
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_approvals');
    }
};

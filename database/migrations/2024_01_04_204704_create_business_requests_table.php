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
        Schema::create('business_requests', function (Blueprint $table) {
            $table->uuid('id')->primary()->index();
            $table->string('type',10);
            $table->jsonb('title')->comment('name of the business');
            $table->string('nature_of_business_company')->comment('nature of the business');
            $table->string('government_id_pdf')->comment('A government issued id for the individual');
            $table->string('email_address')->comment('email address of the business,company');
            $table->string('phone_number')->comment('phone number of business');
            $table->string('physical_address')->comment('physical address of business');
            $table->string('email_address_proprietors_directors')->comment('address of proprietors,directors');
            $table->string('phone_number_proprietors_directors')->comment('phone number of business proprietors');
            $table->string('physical_address_of_directors')->nullable();
            $table->string('name_of_directors')->nullable();
            $table->string('signature_of_proprietors_directors_pdf');
            $table->string('passport_photograph_of_directors_pdf')->nullable();
            $table->string('utility_bill_pdf')->nullable();
            $table->boolean('status')->default(0);
            $table->longText('comments')->nullable();
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
        Schema::dropIfExists('business_requests');
    }
};

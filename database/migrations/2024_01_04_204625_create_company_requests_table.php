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
        Schema::create('company_requests', function (Blueprint $table) {
            $table->uuid('id')->primary()->index();
            $table->string('title')->comment('this is an array of business names concatenated from the three fields');
            $table->string('nature_of_company');
            $table->string('government_id_pdf');
            $table->string('email_address')->comment('companys emails address');
            $table->string('phone_number');
            $table->string('physical_address');
            $table->string('name_of_directors');
            $table->string('physical_address_of_directors');
            $table->string('email_address_of_directors');
            $table->string('phone_number_of_directors');
            $table->string('passport_photograph_of_directors_pdf');
            $table->string('signature_of_directors_pdf');
            $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists('company_requests');
    }
};

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
        Schema::create('services', function (Blueprint $table) {
            $table->uuid('id')->primary()->index();
            $table->string('type')->comment('this can be utility,airtime,internet electricity');
            $table->string('provider')->comment('This refers to the name of the single entity offering this service, mtn,dstv');
            $table->string('description')->comment('Give a brief overview of the service and the benefits');
            $table->double('amount')->nullable();
            $table->double('commission')->nullable()->default(0);
            $table->boolean('is_recurring')->default(0);
            $table->double('discount')->nullable()->default(0);
            $table->tinyInteger('status')->default(1)->comment('confirm if the service is active or disabled.');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate();
            $table->softDeletesTz();
        });
        //service one time: [type:airtime,bills,data,service],provider[mtn,1vault,IKEDC],amount,SERVICEID=>unique,status
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};

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
            $table->string('title');
            $table->string('type')->comment('this can be utility,airtime,data,internet,electricity,social_media_subscription,legal,service.buiness_registration');
            $table->string('provider')->comment('This refers to the name of the single entity offering this service, mtn,dstv');
            $table->string('description')->comment('Give a brief overview xof the service and the benefits');
            $table->string('image')->nullable();
            $table->double('amount')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('category')->comment('social_media,business_registration,legal,pos,store_front');
            $table->double('commission')->nullable()->default(0);
            $table->boolean('is_recurring')->default(0);
            $table->boolean('requires_payment')->default(1);
            $table->string('billing_cycle')->comment('this is how the queue job will charge the subscription plan, one-time,monthly,quarterly,yearly');
            $table->boolean('is_request')->default(0);
            $table->jsonb('benefit')->nullable();
            $table->integer('discount')->nullable()->default(0);
            $table->boolean('has_discount')->default(0);
            $table->boolean('status')->default(1)->comment('confirm if the service is active or disabled.');
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

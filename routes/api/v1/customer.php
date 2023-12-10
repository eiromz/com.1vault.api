<?php

use Src\Customer\App\Http\CompleteCustomerProfileCtrl;
use Src\Customer\App\Http\RegisterCustomerCtrl;
use Src\Customer\App\Http\ResendOtpCtrl;
use Src\Customer\App\Http\VerifyEmailCtrl;
use Src\Customer\App\Http\AuthenticateSessionCtrl;


Route::post('auth/register',[RegisterCustomerCtrl::class,'store']);
Route::post('auth/verify-email',[VerifyEmailCtrl::class,'store']);
Route::post('auth/login',[AuthenticateSessionCtrl::class,'store']);
Route::post('auth/resend-otp',ResendOtpCtrl::class);
//resend otp

Route::middleware(['email.hasBeenVerified','auth:sanctum'])->group(function(){
    Route::post('auth/complete-profile', CompleteCustomerProfileCtrl::class);
    Route::post('auth/logout',[AuthenticateSessionCtrl::class,'destroy']);
});

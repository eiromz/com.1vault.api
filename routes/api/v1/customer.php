<?php

use Src\Customer\App\Http\RegisterCustomerCtrl;
use Src\Customer\App\Http\VerifyEmailCtrl;


Route::post('auth/register',[RegisterCustomerCtrl::class,'store']);
Route::post('auth/verify-email',[VerifyEmailCtrl::class,'store']);

Route::middleware('email.hasBeenVerified')->group(function(){
    Route::post('auth/complete-profile',[RegisterCustomerCtrl::class,'store']);
});

<?php

use App\Http\Controllers\Src\Customer\App\Http\VerifyEmailCtrl;
use Src\Customer\App\Http\RegisterCustomerCtrl;

Route::post('auth/register',[RegisterCustomerCtrl::class,'store']);
Route::post('auth/verify-email',[VerifyEmailCtrl::class,'store']);
Route::post('auth/complete-profile',[RegisterCustomerCtrl::class,'store']);

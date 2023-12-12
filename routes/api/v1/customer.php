<?php

use Src\Customer\App\Http\AuthenticateSessionCtrl;
use Src\Customer\App\Http\CompleteCustomerProfileCtrl;
use Src\Customer\App\Http\RegisterCustomerCtrl;
use Src\Customer\App\Http\ResendOtpCtrl;
use Src\Customer\App\Http\TransactionPinCtrl;
use Src\Customer\App\Http\VerifyEmailCtrl;
use Src\Customer\App\Http\ForgotPasswordCtrl;
use Src\Customer\App\Http\VerifyOtpCtrl;
use Src\Customer\App\Http\ResetPasswordCtrl;
use Src\Customer\App\Http\ProfileCtrl;
use Src\Customer\App\Http\ChangePasswordCtrl;

Route::post('auth/register', [RegisterCustomerCtrl::class, 'store']);
Route::post('auth/verify-email', [VerifyEmailCtrl::class, 'store']);
Route::post('auth/login', [AuthenticateSessionCtrl::class, 'store']);
Route::post('auth/resend-otp', ResendOtpCtrl::class);
Route::post('auth/forgot-password', ForgotPasswordCtrl::class);
Route::post('auth/verify-otp',VerifyOtpCtrl::class);
Route::post('auth/reset-password', ResetPasswordCtrl::class);
//resend otp

Route::middleware(['email.hasBeenVerified', 'auth:sanctum'])->group(function () {
    Route::post('auth/complete-profile', CompleteCustomerProfileCtrl::class);
    Route::post('auth/logout', [AuthenticateSessionCtrl::class, 'destroy']);
    Route::get('profile', [ProfileCtrl::class, 'index']);
    Route::post('profile/delete-account', [ProfileCtrl::class, 'destroy']);
    Route::post('profile', [ProfileCtrl::class, 'update']);
    //update transaction pin
    Route::post('profile/transaction-pin',[TransactionPinCtrl::class,'store']);
    Route::post('profile/forgot-transaction-pin',[TransactionPinCtrl::class,'update']);
    Route::post('profile/change-password',ChangePasswordCtrl::class);

    //Route::post('auth/delete-account', [ProfileCtrl::class, 'destroy']);
    //Route::get('auth/profile', [ProfileCtrl::class, 'store']);
    //Route::post('auth/change-password', [ChangePasswordCtrl::class, 'store']);
    //change transaction pin
    //forgot pin
});

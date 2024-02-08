<?php

use Src\Accounting\App\Http\StoreFrontCtrl;
use Src\Merchant\App\Http\AuthenticateSessionCtrl;
use Src\Merchant\App\Http\ChangePasswordCtrl;
use Src\Merchant\App\Http\CompleteCustomerProfileCtrl;
use Src\Merchant\App\Http\ForgotPasswordCtrl;
use Src\Merchant\App\Http\KnowYourCustomerCtrl;
use Src\Merchant\App\Http\ProfileCtrl;
use Src\Merchant\App\Http\RegisterCustomerCtrl;
use Src\Merchant\App\Http\ResendOtpCtrl;
use Src\Merchant\App\Http\ResetPasswordCtrl;
use Src\Merchant\App\Http\StaffCtrl;
use Src\Merchant\App\Http\TransactionPinCtrl;
use Src\Merchant\App\Http\VerifyEmailCtrl;
use Src\Merchant\App\Http\VerifyOtpCtrl;

Route::post('auth/register', [RegisterCustomerCtrl::class, 'store']);
Route::post('auth/verify-email', [VerifyEmailCtrl::class, 'store']);
Route::post('auth/login', [AuthenticateSessionCtrl::class, 'store']);
Route::post('auth/resend-otp', ResendOtpCtrl::class);
Route::post('auth/forgot-password', ForgotPasswordCtrl::class);
Route::post('auth/verify-otp', VerifyOtpCtrl::class);
Route::post('auth/reset-password', ResetPasswordCtrl::class);
//resend otp

//middlewares to add
//a middleware that has the owner and member id attached to it.
//a middleware that can detect when a person has not completed their registration.
//a middleware that prevents users from accessing places where they have no abilites for.
//a middleware that checks if a user has a transaction pin added to their account.
//kyc validation to generate bank account number middleware for any customer.
Route::middleware(['email.hasBeenVerified', 'auth:sanctum'])->group(function () {
    Route::post('auth/complete-profile', CompleteCustomerProfileCtrl::class);
    Route::post('auth/logout', [AuthenticateSessionCtrl::class, 'destroy']);
    Route::get('profile', [ProfileCtrl::class, 'index']);
    Route::post('profile/delete-account', [ProfileCtrl::class, 'destroy']);
    Route::post('profile', [ProfileCtrl::class, 'update']);
    //update transaction pin

    Route::post('profile/kyc', KnowYourCustomerCtrl::class);

    Route::post('profile/transaction-pin', [TransactionPinCtrl::class, 'store']);
    Route::post('profile/change-password', ChangePasswordCtrl::class);

    Route::get('staff',[StaffCtrl::class,'index']);
    Route::post('staff',[StaffCtrl::class,'store']);
    Route::get('staff/view/{staff}',[StaffCtrl::class,'view']);
    Route::post('staff/edit/{staff}',[StaffCtrl::class,'update']);
    Route::post('staff/delete/{staff}',[StaffCtrl::class,'update']);

});

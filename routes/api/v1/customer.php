<?php

use Src\Customer\App\Http\AuthenticateSessionCtrl;
use Src\Customer\App\Http\ChangePasswordCtrl;
use Src\Customer\App\Http\CompleteCustomerProfileCtrl;
use Src\Customer\App\Http\ForgotPasswordCtrl;
use Src\Customer\App\Http\KnowYourCustomerCtrl;
use Src\Customer\App\Http\ProfileCtrl;
use Src\Customer\App\Http\RegisterCustomerCtrl;
use Src\Customer\App\Http\ResendOtpCtrl;
use Src\Customer\App\Http\ResetPasswordCtrl;
use Src\Customer\App\Http\StaffCtrl;
use Src\Customer\App\Http\TransactionPinCtrl;
use Src\Customer\App\Http\VerifyEmailCtrl;
use Src\Customer\App\Http\VerifyOtpCtrl;

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
    Route::post('profile/staff', [StaffCtrl::class, 'index']);
    Route::post('profile/delete-staff', [StaffCtrl::class, 'destroy']);
    Route::post('profile/create-staff', [StaffCtrl::class, 'store']);
    Route::post('profile/update-staff', [StaffCtrl::class, 'update']);

    Route::post('store-front/',[StoreFrontCtrl::class,'store']);
});

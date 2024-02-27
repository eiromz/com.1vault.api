<?php

use Src\Wallets\Payments\App\Http\BeneficiaryCtrl;
use Src\Wallets\Payments\App\Http\CartCtrl;
use Src\Wallets\Payments\App\Http\JournalCtrl;
use Src\Wallets\Payments\App\Http\PayNowCtrl;
use Src\Wallets\Payments\App\Http\Providus\Bills\BillCtrl;
use Src\Wallets\Payments\App\Http\Providus\Bills\CategoriesCtrl;
use Src\Wallets\Payments\App\Http\Providus\FetchBankAccountInformationCtrl;
use Src\Wallets\Payments\App\Http\Providus\FetchBankCtrl;
use Src\Wallets\Payments\App\Http\Providus\TransferCtrl;
use Src\Wallets\Payments\App\Http\WebhookCtrl;
use Src\Wallets\Payments\App\Http\WalletAccountSearchCtrl;

//add header to this request via the middleware
Route::post('/providus/webhook', WebhookCtrl::class);

//

Route::middleware(['email.hasBeenVerified', 'auth:sanctum'])->group(function () {
    //search for a user
    //insufficient balance
    //pin validation
    //add owner check to this route
    Route::post('/wallets/name-search', [WalletAccountSearchCtrl::class, 'index']);
    Route::post('/wallets/journal', [JournalCtrl::class, 'index']);
    Route::post('/wallets/journal/view', [JournalCtrl::class, 'view']);
    Route::post('/wallets/journal/transfer', [JournalCtrl::class, 'store'])
        ->middleware('invalid.accountNumber');
    Route::post('/cart', [CartCtrl::class, 'store']);
    Route::post('/cart/delete', [CartCtrl::class, 'destroy']);
    Route::get('/cart', [CartCtrl::class, 'index']);
    Route::post('/pay-now', [PayNowCtrl::class, 'store']);

    Route::prefix('/beneficiary')->group(function(){
        Route::get('/', [BeneficiaryCtrl::class,'index']);
        Route::get('/view/{beneficiary}', [BeneficiaryCtrl::class,'view']);
    });

    Route::prefix('/providus/nip')->group(function () {
        Route::get('/banks', FetchBankCtrl::class);
        Route::post('/enquiry', FetchBankAccountInformationCtrl::class);
        Route::post('/transfer', TransferCtrl::class)->middleware('invalid.accountNumber');;
    });

    Route::prefix('/providus/bills')->group(function () {
        Route::get('/categories', [CategoriesCtrl::class,'index']);
        Route::get('/categories/{category}', [CategoriesCtrl::class,'view']);
        Route::get('/fields/{bill}', [BillCtrl::class,'index']);
    });
});

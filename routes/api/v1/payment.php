<?php

use Src\Wallets\Payments\App\Http\CartCtrl;
use Src\Wallets\Payments\App\Http\JournalCtrl;
use Src\Wallets\Payments\App\Http\PayNowCtrl;
use Src\Wallets\Payments\App\Http\ProvidusWebhookCtrl;
use Src\Wallets\Payments\App\Http\WalletAccountSearchCtrl;

//add header to this request via the middleware
Route::post('/pr/webhook/notify', ProvidusWebhookCtrl::class);

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
    Route::post('/pay-now/service',[PayNowCtrl::class,'store']);

});

<?php

use Src\Wallets\Payments\App\Http\InterWalletTransferCtrl;
use Src\Wallets\Payments\App\Http\JournalCtrl;
use Src\Wallets\Payments\App\Http\ProvidusWebhookCtrl;
use Src\Wallets\Payments\App\Http\WalletAccountSearchCtrl;

//add header to this request via the middleware
Route::post('/pr/webhook/notify', ProvidusWebhookCtrl::class);

//

Route::middleware(['email.hasBeenVerified', 'auth:sanctum'])->group(function () {
    //search for a user
    //insufficient balance
    //pin validation
    Route::post('/wallets/name-search',[WalletAccountSearchCtrl::class,'index']);
    Route::post('/wallets/journal',[JournalCtrl::class,'index']);
    Route::post('/wallets/journal/view',[JournalCtrl::class,'view']);
    Route::post('/wallets/journal/transfer',[JournalCtrl::class,'store']);
});

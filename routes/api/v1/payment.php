<?php

use Src\Wallets\Payments\App\Http\ProvidusWebhookCtrl;

//add header to this request via the middleware
Route::post('/pr/webhook/notify', ProvidusWebhookCtrl::class);


//

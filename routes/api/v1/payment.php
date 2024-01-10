<?php

use Src\Wallets\Payments\App\Http\ProvidusWebhookCtrl;

Route::post('/pr/webhook/notify', ProvidusWebhookCtrl::class);

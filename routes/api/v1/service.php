<?php

use Src\Services\App\Http\ServiceCtrl;
use Src\Services\App\Http\SubscriptionCtrl;

Route::middleware(['email.hasBeenVerified', 'auth:sanctum'])->group(function () {
    Route::post('/service/create-request', [ServiceCtrl::class, 'store']);
    Route::post('/service', [ServiceCtrl::class, 'index']);
    Route::get('/subscriptions/{status}', [SubscriptionCtrl::class, 'index']);
    Route::get('/subscriptions/view/{subscription}', [SubscriptionCtrl::class, 'view']);
    //Route::get('/business-analytics',BusinessAnalyticsCtrl::class);
});

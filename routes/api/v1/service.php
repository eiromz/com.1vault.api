<?php

use Src\Services\App\Http\ActiveSubscriptionCtrl;
use Src\Services\App\Http\ServiceCtrl;
use Src\Services\App\Http\SubscriptionCtrl;

Route::middleware(['email.hasBeenVerified', 'auth:sanctum'])->group(function () {
    Route::post('/service/create-request', [ServiceCtrl::class, 'store']);
    Route::post('/service', [ServiceCtrl::class, 'index']);
    Route::get('/subscriptions',[SubscriptionCtrl::class,'index']);
    Route::get('/subscriptions/{subscription}',[SubscriptionCtrl::class,'view']);
    Route::post('/subscriptions/cancel',[SubscriptionCtrl::class,'delete']);
    Route::get('/subscriptions/active',[ActiveSubscriptionCtrl::class,'index']);
});

<?php

use Src\Accounting\App\Http\BusinessInformationCtrl;
use Src\Accounting\App\Http\ClientCtrl;
use Src\Accounting\App\Http\InvoiceCtrl;


Route::middleware(['email.hasBeenVerified', 'auth:sanctum'])->group(function () {
    //client for business routes
    Route::post('/client',[ClientCtrl::class,'store']);
    Route::post('/client/view',[ClientCtrl::class,'view']);
    Route::get('/client/{id}/business',[ClientCtrl::class,'index']);
    //business routes
    Route::post('/business',[BusinessInformationCtrl::class,'store']);
    Route::post('/business/view',[BusinessInformationCtrl::class,'view']);
    Route::get('/business',[BusinessInformationCtrl::class,'index']);
    Route::post('/invoice',[InvoiceCtrl::class,'store']);
});

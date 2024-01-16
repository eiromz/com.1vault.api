<?php

use Src\Accounting\App\Http\BusinessInformationCtrl;
use Src\Accounting\App\Http\ClientCtrl;
use Src\Accounting\App\Http\InvoiceCtrl;


Route::middleware(['email.hasBeenVerified', 'auth:sanctum'])->group(function () {
    Route::post('/client',[ClientCtrl::class,'store']);
    Route::post('/client/view',[ClientCtrl::class,'view']);
    Route::post('/business',[BusinessInformationCtrl::class,'store']);
    Route::post('/business/view',[BusinessInformationCtrl::class,'view']);
    Route::post('/invoice',[InvoiceCtrl::class,'store']);
});

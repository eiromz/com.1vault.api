<?php

use Src\Accounting\App\Http\BusinessInformationCtrl;
use Src\Accounting\App\Http\ClientCtrl;
use Src\Accounting\App\Http\InvoiceCtrl;


Route::middleware(['email.hasBeenVerified', 'auth:sanctum'])->group(function () {
    /******** Client Routes ***********/
    Route::post('/client',[ClientCtrl::class,'store']);
    Route::post('/client/view',[ClientCtrl::class,'view']);
    Route::get('/client/{id}/business',[ClientCtrl::class,'index']);
    Route::post('/client/update/{id}',[ClientCtrl::class,'update']);
    /********Business Routes*******/
    Route::post('/business',[BusinessInformationCtrl::class,'store']);
    Route::post('/business/view',[BusinessInformationCtrl::class,'view']);
    Route::get('/business',[BusinessInformationCtrl::class,'index']);
    Route::get('/business/update/{id}',[BusinessInformationCtrl::class,'update']);

    Route::post('/invoice',[InvoiceCtrl::class,'store']);
});

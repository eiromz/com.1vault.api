<?php

use Src\Accounting\App\Http\BusinessInformationCtrl;
use Src\Accounting\App\Http\ClientCtrl;
use Src\Accounting\App\Http\InventoryCtrl;
use Src\Accounting\App\Http\InvoiceCtrl;

Route::middleware(['email.hasBeenVerified', 'auth:sanctum'])->group(function () {
    /******** Client Routes ***********/
    Route::post('/client', [ClientCtrl::class, 'store']);
    Route::post('/client/view', [ClientCtrl::class, 'view']);
    Route::get('/client/{id}/business', [ClientCtrl::class, 'index']);
    Route::post('/client/update/{id}', [ClientCtrl::class, 'update']);

    /********Business Routes*******/
    Route::post('/business', [BusinessInformationCtrl::class, 'store']);
    Route::post('/business/view', [BusinessInformationCtrl::class, 'view']);
    Route::get('/business', [BusinessInformationCtrl::class, 'index']);
    Route::post('/business/update/{id}', [BusinessInformationCtrl::class, 'update']);

    /********Invoice Routes*******/
    Route::post('/invoice', [InvoiceCtrl::class, 'store']);
    Route::post('/invoice/delete', [InvoiceCtrl::class, 'destroy']);

    /********Inventory Routes*******/
    Route::post('/inventory', [InventoryCtrl::class, 'store']);
    Route::get('/inventory/business/{id}', [InventoryCtrl::class, 'index']);
    Route::get('/inventory/{inventory}/business/{business}', [InventoryCtrl::class, 'view']);
    Route::post('/inventory/delete', [InventoryCtrl::class, 'destroy']);
});

<?php

use Src\Accounting\App\Http\BusinessInformationCtrl;
use Src\Accounting\App\Http\ClientCtrl;
use Src\Accounting\App\Http\InventoryCtrl;
use Src\Accounting\App\Http\InvoiceCtrl;
use Src\Accounting\App\Http\ReceiptCtrl;
use Src\Accounting\App\Http\ReportCtrl;
use Src\Accounting\App\Http\StoreFrontCtrl;
use Src\Accounting\App\Http\StoreFrontInventoryCtrl;

Route::middleware(['email.hasBeenVerified', 'auth:sanctum'])->group(function () {
    /******** Client Routes ***********/
    Route::post('/client', [ClientCtrl::class, 'store']);
    Route::post('/client/view', [ClientCtrl::class, 'view']);
    Route::get('/client/{id}/business', [ClientCtrl::class, 'index']);
    Route::post('/client/update/{id}', [ClientCtrl::class, 'update']);
    Route::post('/client/delete', [ClientCtrl::class, 'destroy']);

    /********Business Routes*******/
    Route::post('/business', [BusinessInformationCtrl::class, 'store']);
    Route::post('/business/view', [BusinessInformationCtrl::class, 'view']);
    Route::get('/business', [BusinessInformationCtrl::class, 'index']);
    Route::post('/business/update/{id}', [BusinessInformationCtrl::class, 'update']);
    Route::post('/business/delete', [BusinessInformationCtrl::class, 'destroy']);

    /********Invoice Routes*******/
    Route::post('/invoice', [InvoiceCtrl::class, 'store']);
    Route::post('/invoice/edit/{id}', [InvoiceCtrl::class, 'update']);
    Route::get('/invoice/business/{business}', [InvoiceCtrl::class, 'index']);
    Route::get('/invoice/{invoice}/business/{business}', [InvoiceCtrl::class, 'view']);
    Route::post('/invoice/delete', [InvoiceCtrl::class, 'destroy']);

    /********Receipt Routes*******/
    Route::get('/receipt/business/{business}', [ReceiptCtrl::class, 'index']);
    Route::get('/receipt/{receipt}/business/{business}', [ReceiptCtrl::class, 'view']);
    Route::post('/receipt', [ReceiptCtrl::class, 'store']);
    Route::post('/receipt/edit/{id}', [ReceiptCtrl::class, 'update']);
    Route::post('/receipt/delete', [ReceiptCtrl::class, 'destroy']);

    Route::post('/report', [ReportCtrl::class, 'index']);

    /********Inventory Routes*******/
    Route::post('/inventory', [InventoryCtrl::class, 'store']);
    Route::post('/inventory/edit/{id}', [InventoryCtrl::class, 'edit']);
    Route::get('/inventory/business/{id}', [InventoryCtrl::class, 'index']);
    Route::get('/inventory/{inventory}/business/{business}', [InventoryCtrl::class, 'view']);
    Route::post('/inventory/delete', [InventoryCtrl::class, 'destroy']);

    Route::post('/store-front', [StoreFrontCtrl::class, 'store']);
    Route::post('/store-front/inventory', [StoreFrontInventoryCtrl::class, 'store']);
    Route::post('/store-front/inventory/delete', [StoreFrontInventoryCtrl::class, 'destroy']);
    Route::get('/store-front/inventory/{inventory}/business/{business}', [StoreFrontInventoryCtrl::class, 'view']);
    Route::post('/store-front/inventory/edit/{id}', [StoreFrontInventoryCtrl::class, 'edit']);
    Route::get('/store-front/inventory/business/{id}', [StoreFrontInventoryCtrl::class, 'index']);
});

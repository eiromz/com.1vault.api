<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    require __DIR__.'/api/v1/customer.php';
    require __DIR__.'/api/v1/payment.php';
    require __DIR__.'/api/v1/service.php';
    require __DIR__.'/api/v1/accounting.php';
    require __DIR__.'/api/v1/general.php';
});

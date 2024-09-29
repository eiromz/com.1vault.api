<?php

use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

Route::prefix('v1')->group(function () {
    require __DIR__.'/api/v1/customer.php';
    require __DIR__.'/api/v1/payment.php';
    require __DIR__.'/api/v1/service.php';
    require __DIR__.'/api/v1/accounting.php';
    require __DIR__.'/api/v1/general.php';
});

Route::fallback(function () {
    return jsonResponse(Response::HTTP_BAD_REQUEST, ['message' => 'we could not complete the request']);
});

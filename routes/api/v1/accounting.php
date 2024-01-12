<?php

use Src\Accounting\App\Http\BusinsessInformationCtrl;

Route::middleware(['email.hasBeenVerified', 'auth:sanctum'])->group(function () {
    //Route::post('business', BusinsessInformationCtrl::class);
    //Route::post('business', [AuthenticateSessionCtrl::class, 'destroy']);
});

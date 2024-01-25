<?php

use Src\Services\App\Http\ServiceCtrl;

Route::middleware(['email.hasBeenVerified', 'auth:sanctum'])->group(function () {
    Route::post('service/create-request', [ServiceCtrl::class, 'store']);
    Route::post('service', [ServiceCtrl::class, 'index']);
});

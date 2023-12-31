<?php

use App\Http\Controllers\Api\WelcomeController;
use App\Http\Controllers\UploadCtrl;
use App\Models\Profile;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Src\Wallets\Payments\App\Http\ProvidusWebhookCtrl;
use Symfony\Component\HttpFoundation\Response;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/', WelcomeController::class);

Route::post('/v1/pr/webhook/notify', ProvidusWebhookCtrl::class);

Route::post('/v1/upload-file', UploadCtrl::class);

Route::prefix('v1')->middleware(['json.response'])->group(function () {
    require __DIR__.'/api/v1/customer.php';
    require __DIR__.'/api/v1/payment.php';
    require __DIR__.'/api/v1/service.php';
});

//'middleware' => 'throttle:3'

Route::get('v1/states', function () {
    $state = State::query()->select(['id', 'name'])->where('country_id', 160)->get();

    return jsonResponse(Response::HTTP_OK, $state);
});

Route::get('v1/doc-types', function () {
    return jsonResponse(Response::HTTP_OK, Profile::DOC_TYPES);
});

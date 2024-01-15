<?php

use App\Http\Controllers\UploadCtrl;
use App\Models\Profile;
use App\Models\State;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

Route::get('/doc-types', function () {
    return jsonResponse(Response::HTTP_OK, Profile::DOC_TYPES);
});

Route::get('/states', function () {
    $state = State::query()->select(['id', 'name'])->where('country_id', 160)->get();

    return jsonResponse(Response::HTTP_OK, $state);
});

Route::post('/upload-file', UploadCtrl::class)->middleware('throttle:3');

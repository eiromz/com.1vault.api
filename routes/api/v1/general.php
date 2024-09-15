<?php

use App\Http\Controllers\UploadCtrl;
use App\Models\Business;
use App\Models\PosRequest;
use App\Models\Profile;
use App\Models\State;
use Illuminate\Support\Facades\Route;
use Src\Accounting\App\Http\ReportCtrl;
use Symfony\Component\HttpFoundation\Response;

Route::get('/doc-types', function () {
    $profile = (new Profile)->doctypes();

    return jsonResponse(Response::HTTP_OK, $profile);
});

Route::get('/states', function () {
    $state = State::query()->select(['id', 'name'])->where('country_id', 160)->get();

    return jsonResponse(Response::HTTP_OK, $state);
});

Route::post('/upload-file', UploadCtrl::class)->middleware('throttle:10');

Route::get('/pos/sectors', function () {
    return jsonResponse(Response::HTTP_OK, (new PosRequest)->sectors());
});

Route::get('/pos/business-types', function () {
    return jsonResponse(Response::HTTP_OK, (new PosRequest)->businessTypes());
});

Route::get('/pos/card-types', function () {
    return jsonResponse(Response::HTTP_OK, (new PosRequest)->cardTypes());
});

Route::get('/business/sectors', function () {
    return jsonResponse(Response::HTTP_OK, (new Business)->businessSector());
});

Route::post('download/pdf', [ReportCtrl::class, 'index'])->middleware('auth:sanctum');

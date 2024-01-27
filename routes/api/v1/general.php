<?php

use App\Http\Controllers\UploadCtrl;
use App\Models\Business;
use App\Models\PosRequest;
use App\Models\Profile;
use App\Models\State;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\HttpFoundation\Response;



Route::get('/doc-types', function () {
    return jsonResponse(Response::HTTP_OK, Profile::DOC_TYPES);
});

Route::get('/states', function () {
    $state = State::query()->select(['id', 'name'])->where('country_id', 160)->get();

    return jsonResponse(Response::HTTP_OK, $state);
});

Route::post('/upload-file', UploadCtrl::class)->middleware('throttle:3');

Route::get('/pos/sectors', function () {
    return jsonResponse(Response::HTTP_OK, (new PosRequest())->sectors());
});

Route::get('/pos/business-types', function () {
    return jsonResponse(Response::HTTP_OK, (new PosRequest())->businessTypes());
});

Route::get('/pos/card-types', function () {
    return jsonResponse(Response::HTTP_OK, (new PosRequest())->cardTypes());
});

Route::get('/business/sectors', function () {
    return jsonResponse(Response::HTTP_OK, (new Business())->businessSector());
});


Route::post('/download/pdf', function (Request $request) {
    $model = \App\Models\Inventory::first();
    $request->validate([
        'type' => ['required', 'in:sales,debtors,invoice,receipt,pos'],
        'identifier' => ['required'],
    ]);

    $getView = match ($request->type) {
        'sales' => 'pdf-template.sales',
        'debtors' => 'pdf-template.debtors',
        'receipt' => 'pdf-template.receipt',
        'invoice' => 'pdf-template.invoice',
    };

    $getModel = match ($request->type) {
        'sales','debtors','invoice' => App\Models\Invoice::query(),
        'receipt' => App\Models\Receipt::query(),
    };

    $getModel->findOrFail($request->identifier);

    $pdf = Pdf::loadView($getView, ['welcome']);

    return $pdf->download('invoice.pdf');
});

Route::post('/testing',function (Request $request){

    $request->validate([
        'type' => ['required', 'in:sales,debtors,invoice,receipt,pos'],
        'identifier' => ['required'],
    ]);

    $url = config('app.url').'/api/v1/download/pdf';



    return Browsershot::url($url)
        ->noSandbox()
        ->post([
            'type' => $request->type,
            'identifier' => $request->identifier
        ])
        ->screenshot();
});

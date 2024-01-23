<?php

use App\Http\Controllers\UploadCtrl;
use App\Models\Profile;
use App\Models\State;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

Route::get('/doc-types', function () {
    return jsonResponse(Response::HTTP_OK, Profile::DOC_TYPES);
});

Route::get('/states', function () {
    $state = State::query()->select(['id', 'name'])->where('country_id', 160)->get();

    return jsonResponse(Response::HTTP_OK, $state);
});

Route::post('/upload-file', UploadCtrl::class)->middleware('throttle:3');

Route::post('/download/pdf', function(Request $request){
    $request->validate([
        'type' => ['required','in:sales,debtors,invoice,receipt'],
        'identifier' => ['required']
    ]);

    $getView = match($request->type){
        'sales' => 'pdf-template.sales',
        'debtors' => 'pdf-template.debtors',
        'receipt' => 'pdf-template.receipt',
        'invoice' => 'pdf-template.invoice',
    };

    $getModel = match($request->type) {
        'sales','debtors','invoice' => App\Models\Invoice::query(),
        'receipt' => App\Models\Receipt::query(),
    };

    //dd($getModel->findOrFail($request->identifier));

    $pdf = Pdf::loadView($getView, ['welcome']);
    return $pdf->download('invoice.pdf');

    return view($getView);
});

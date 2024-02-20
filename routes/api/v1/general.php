<?php

use App\Http\Controllers\UploadCtrl;
use App\Models\Business;
use App\Models\Invoice;
use App\Models\PosRequest;
use App\Models\Profile;
use App\Models\Receipt;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\Browsershot\Browsershot;
use Src\Accounting\App\Http\ReportCtrl;
use Symfony\Component\HttpFoundation\Response;

Route::get('/doc-types', function () {
    $profile = (new Profile())->doctypes();

    return jsonResponse(Response::HTTP_OK, $profile);
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

//Route::post('download/pdf', [ReportCtrl::class, 'download']);
Route::post('/download/pdf', function (Request $request) {
    $request->validate([
        'type' => ['required', 'in:sales,debtors,invoice,receipt,pos'],
        'identifier' => ['required'],
        'start_date' => ['required_if:type,sales,debtors', 'date_format:Y-m-d'],
        'end_date' => ['required_if:type,sales,debtors', 'date_format:Y-m-d'],
    ]);

    $getView = match ($request->type) {
        'sales' => 'pdf-template.sales',
        'debtors' => 'pdf-template.debtors',
        'receipt' => 'pdf-template.receipt',
        'invoice' => 'pdf-template.invoice',
    };

    $getView = match ($request->type) {
        'sales' => 'pdf-template.sales',
        'debtors' => 'pdf-template.debtors',
        'receipt' => 'pdf-template.receipt',
        'invoice' => 'pdf-template.invoice',
        'pos' => 'pdf-template.pos',
    };

    $getModel = match ($request->type) {
        'receipt'   => Receipt::query(),
        'pos'       => PosRequest::query(),
        'invoice' => Invoice::query(),
        'sales','debtors' => Invoice::query()
    };

    $data = (in_array($request->type,['receipt','pos','invoice']))  ? $getModel->findOrFail($request->identifier) : $getModel->where(
        'business_id','=');

    dd($data);

    $pdf = Pdf::loadView($getView, ['welcome']);

    return $pdf->download('invoice.pdf');
})->middleware('auth:sanctum');;


Route::post('/testing', function (Request $request) {

    $request->validate([
        'type' => ['required', 'in:sales,debtors,invoice,receipt,pos'],
        'identifier' => ['required'],
    ]);

    $url = config('app.url').'/api/v1/download/pdf';

    return Browsershot::url($url)
        ->noSandbox()
        ->post([
            'type' => $request->type,
            'identifier' => $request->identifier,
        ])
        ->screenshot();
});

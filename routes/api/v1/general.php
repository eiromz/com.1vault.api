<?php

use App\Http\Controllers\UploadCtrl;
use App\Models\Profile;
use App\Models\State;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
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

Route::get('/pos/categories', function () {
    return jsonResponse(Response::HTTP_OK, [
        'sole owner', 'partnership', 'limited liability company', 'public limited company', 'others',
    ]);
});

Route::get('/pos/business-types', function () {
    return jsonResponse(Response::HTTP_OK, [
        'store/supermarket', 'restaurants', 'wholesale/distributor', 'telecoms', 'fuel station',
        'fast food', 'hotel/guest house', 'logistics', 'church/ngo', 'hospital', 'airlines', 'travel agencies',
        'embassy', 'education/schools', 'others',
    ]);
});

Route::get('/pos/card-types', function () {
    return jsonResponse(Response::HTTP_OK, [
        'local card', 'international mastercard', 'international visa card',
    ]);
});

Route::post('/download/pdf', function (Request $request) {
    $request->validate([
        'type' => ['required', 'in:sales,debtors,invoice,receipt'],
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

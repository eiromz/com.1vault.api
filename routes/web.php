<?php

use Illuminate\Support\Facades\Route;
use Spatie\Browsershot\Browsershot;
use Spatie\LaravelPdf\Facades\Pdf;
use function Spatie\LaravelPdf\Support\pdf;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::get('/view/template/receipt', function () {
    return Pdf::view('pdf-template.receipt',['welcome'])
        ->withBrowsershot(function (Browsershot $browsershot) {
            $browsershot->setNodeBinary(config('app.which_node'))
                ->setNpmBinary(config('app.which_npm'));
        })->save('sample.pdf');
});

Route::get('/view/template/sales', function () {
    return view('pdf-template.sales');
});

Route::get('/view/template/debtors', function () {
    return view('pdf-template.debtors');
});

require __DIR__.'/auth.php';

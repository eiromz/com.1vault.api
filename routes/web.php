<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/view/template/receipt', function(){
    return view('pdf-template.receipt');
});

Route::get('/view/template/sales', function(){
    return view('pdf-template.sales');
});

Route::get('/view/template/debtors', function(){
    return view('pdf-template.debtors');
});

require __DIR__.'/auth.php';

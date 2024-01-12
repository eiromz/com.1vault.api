<?php

use Src\Accounting\App\Http\BusinessInformationCtrl;
use Src\Accounting\App\Http\ClientCtrl;


Route::post('/client',[ClientCtrl::class,'store']);
Route::post('/business',[BusinessInformationCtrl::class,'store']);

<?php
use App\Http\Controllers\Api\WelcomeController;
use App\Http\Controllers\UploadCtrl;
use App\Models\Customer;
use App\Support\Firebase;
use Illuminate\Support\Facades\Route;

Route::get('/', WelcomeController::class);

Route::get('/v1/testing', function () {
    $notification = [
        'title' => 'Credit Notification',
        'body' => 'New credit to your account.',
    ];
    $customer = Customer::query()
        ->where('email', '=', 'crayolu@gmail.com')
        ->with(['profile'])
        ->first();
    $firebase = new Firebase($customer->firebase_token);
    $firebase->sendMessageWithToken($notification, ['Transactions']);
});

Route::post('/v1/upload-file', UploadCtrl::class);

Route::prefix('v1')->middleware(['json.response'])->group(function () {
    require __DIR__.'/api/v1/customer.php';
    require __DIR__.'/api/v1/payment.php';
    require __DIR__.'/api/v1/service.php';
    require __DIR__.'/api/v1/accounting.php';
    require __DIR__.'/api/v1/general.php';
});

//'middleware' => 'throttle:3'

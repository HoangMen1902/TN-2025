<?php

use App\Http\Middleware\CartCheckoutMiddleware;
use Illuminate\Support\Facades\Route;
use Modules\PaymentModule\Http\Controllers\PaymentModuleController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('paymentmodules', PaymentModuleController::class)->names('paymentmodule');
});

Route::get('/thanh-toan', [PaymentModuleController::class, 'index']);
Route::middleware(['web', CartCheckoutMiddleware::class])->group(function () {
    Route::post('/thanh-toan', [PaymentModuleController::class, 'paymentPage']);
});

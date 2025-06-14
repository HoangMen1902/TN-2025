<?php

use App\Http\Middleware\CartCheckoutMiddleware;
use Illuminate\Support\Facades\Route;
use Modules\PaymentModule\Http\Controllers\PaymentModuleController;
use Illuminate\Http\Request;
use App\Models\Order;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('paymentmodules', PaymentModuleController::class)->names('paymentmodule');
});

Route::get('/thanh-toan', [PaymentModuleController::class, 'index']);
Route::middleware(['web', CartCheckoutMiddleware::class])->group(function () {
    Route::post('/thanh-toan', [PaymentModuleController::class, 'paymentPage']);
});
Route::get('/cam-on-quy-khach/{payment_id}', [PaymentModuleController::class, 'thanks'])->name('thanks');
 
Route::post('/checkout', [PaymentModuleController::class, 'store'])->name('checkout.store');

Route::get('/vnpay/return', [PaymentModuleController::class, 'vnpayCallback'])->name('vnpay.callback');
Route::get('/international-return/{checkout_id}/{payment_id}', [PaymentModuleController::class, 'internationalCallback'])->name('international');
Route::post('/payment/webhook/payos', [PaymentModuleController::class, 'payosWebhook'])->name('payment.payosWebhook');

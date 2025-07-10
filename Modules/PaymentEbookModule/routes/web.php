<?php

use Illuminate\Support\Facades\Route;
use Modules\PaymentEbookModule\Http\Controllers\PaymentEbookModuleController;

Route::prefix('ebook-payment')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [PaymentEbookModuleController::class, 'index'])->name('ebook.payment');
    Route::post('/', [PaymentEbookModuleController::class, 'store'])->name('ebook.checkout.store');
    Route::get('/thanks/{payment_id}', [PaymentEbookModuleController::class, 'thanks'])->name('ebook.thanks');
});

Route::get('/ebook-vnpay/return', [PaymentEbookModuleController::class, 'vnpayCallback'])->name('ebook.vnpay.callback');
Route::get('/ebook-international-return/{checkout_id}/{payment_id}', [PaymentEbookModuleController::class, 'internationalCallback'])->name('ebook.international');
Route::post('/ebook-payment/webhook/payos', [PaymentEbookModuleController::class, 'payosWebhook'])->name('ebook.payos.webhook');
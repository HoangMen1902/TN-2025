<?php

use Illuminate\Support\Facades\Route;
use Modules\VoucherModule\Http\Controllers\VoucherModuleController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('vouchermodules', VoucherModuleController::class)->names('vouchermodule');
});

Route::get('/voucher', [VoucherModuleController::class, 'index'])->name('voucher');
Route::post('/voucher/store', [VoucherModuleController::class, 'store'])->name('voucher.store');
Route::post('/voucher/redeem', [VoucherModuleController::class, 'redeem'])->name('voucher.redeem');

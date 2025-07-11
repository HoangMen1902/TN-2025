<?php

use Illuminate\Support\Facades\Route;
use Modules\VoucherModule\Http\Controllers\VoucherModuleController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('vouchermodules', VoucherModuleController::class)->names('vouchermodule');
});

Route::get('/voucher', [VoucherModuleController::class, 'index'])->name('voucher.index');

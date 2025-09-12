<?php

use Illuminate\Support\Facades\Route;
use Modules\VoucherModule\Http\Controllers\VoucherModuleController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('vouchermodules', VoucherModuleController::class)->names('vouchermodule');
});

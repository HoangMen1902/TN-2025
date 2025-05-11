<?php

use Illuminate\Support\Facades\Route;
use Modules\PaymentModule\Http\Controllers\PaymentModuleController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('paymentmodules', PaymentModuleController::class)->names('paymentmodule');
});

<?php

use Illuminate\Support\Facades\Route;
use Modules\PaymentEbookModule\Http\Controllers\PaymentEbookModuleController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('paymentebookmodules', PaymentEbookModuleController::class)->names('paymentebookmodule');
});

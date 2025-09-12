<?php

use Illuminate\Support\Facades\Route;
use Modules\ProductModule\Http\Controllers\ProductModuleController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('productmodules', ProductModuleController::class)->names('productmodule');
});

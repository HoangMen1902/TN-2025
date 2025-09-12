<?php

use Illuminate\Support\Facades\Route;
use Modules\DetailModule\Http\Controllers\DetailModuleController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('detailmodules', DetailModuleController::class)->names('detailmodule');
});

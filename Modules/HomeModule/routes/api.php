<?php

use Illuminate\Support\Facades\Route;
use Modules\HomeModule\Http\Controllers\HomeModuleController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('homemodules', HomeModuleController::class)->names('homemodule');
});

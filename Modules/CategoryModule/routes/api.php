<?php

use Illuminate\Support\Facades\Route;
use Modules\CategoryModule\Http\Controllers\CategoryModuleController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('categorymodules', CategoryModuleController::class)->names('categorymodule');
});

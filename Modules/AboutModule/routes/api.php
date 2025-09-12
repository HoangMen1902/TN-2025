<?php

use Illuminate\Support\Facades\Route;
use Modules\AboutModule\Http\Controllers\AboutModuleController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('aboutmodules', AboutModuleController::class)->names('aboutmodule');
});

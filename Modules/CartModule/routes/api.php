<?php

use Illuminate\Support\Facades\Route;
use Modules\CartModule\Http\Controllers\CartModuleController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('cartmodules', CartModuleController::class)->names('cartmodule');
});

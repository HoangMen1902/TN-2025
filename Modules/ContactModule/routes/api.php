<?php

use Illuminate\Support\Facades\Route;
use Modules\ContactModule\Http\Controllers\ContactModuleController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('contactmodules', ContactModuleController::class)->names('contactmodule');
});

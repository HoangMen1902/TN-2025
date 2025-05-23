<?php

use Illuminate\Support\Facades\Route;
use Modules\SuggestModule\Http\Controllers\SuggestModuleController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('suggestmodules', SuggestModuleController::class)->names('suggestmodule');
});

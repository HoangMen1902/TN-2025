<?php

use Illuminate\Support\Facades\Route;
use Modules\SearchOrderModule\Http\Controllers\SearchOrderModuleController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('searchordermodules', SearchOrderModuleController::class)->names('searchordermodule');
});

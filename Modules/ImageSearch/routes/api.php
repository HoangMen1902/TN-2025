<?php

use Illuminate\Support\Facades\Route;
use Modules\ImageSearch\Http\Controllers\ImageSearchController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('imagesearches', ImageSearchController::class)->names('imagesearch');
});

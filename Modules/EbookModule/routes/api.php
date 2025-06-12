<?php

use Illuminate\Support\Facades\Route;
use Modules\EbookModule\Http\Controllers\EbookModuleController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('ebookmodules', EbookModuleController::class)->names('ebookmodule');
});

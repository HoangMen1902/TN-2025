<?php

use Illuminate\Support\Facades\Route;
use Modules\MiniGameModule\Http\Controllers\MiniGameModuleController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('minigamemodules', MiniGameModuleController::class)->names('minigamemodule');
});

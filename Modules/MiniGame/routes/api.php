<?php

use Illuminate\Support\Facades\Route;
use Modules\MiniGame\Http\Controllers\MiniGameController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('minigames', MiniGameController::class)->names('minigame');
});

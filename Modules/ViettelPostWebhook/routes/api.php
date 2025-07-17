<?php

use Illuminate\Support\Facades\Route;
use Modules\ViettelPostWebhook\Http\Controllers\ViettelPostWebhookController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('viettelpostwebhooks', ViettelPostWebhookController::class)->names('viettelpostwebhook');
});

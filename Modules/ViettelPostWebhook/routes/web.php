<?php

use Illuminate\Support\Facades\Route;
use Modules\ViettelPostWebhook\Http\Controllers\ViettelPostWebhookController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('viettelpostwebhooks', ViettelPostWebhookController::class)->names('viettelpostwebhook');
});

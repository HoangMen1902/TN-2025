<?php

use Illuminate\Support\Facades\Route;
use Modules\ViettelPostWebhook\Http\Controllers\ViettelPostWebhookController;

Route::post('/webhook-vtp', [ViettelPostWebhookController::class, 'handle']);

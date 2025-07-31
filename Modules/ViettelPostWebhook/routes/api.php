<?php

use Illuminate\Support\Facades\Route;
use Modules\ViettelPostWebhook\Http\Controllers\ViettelPostWebhookController;

Route::any('/webhook-vtp', [ViettelPostWebhookController::class, 'handle']);

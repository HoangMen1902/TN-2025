<?php

use Illuminate\Support\Facades\Route;
use Modules\OrderDetail\Http\Controllers\OrderDetailController;
use Modules\OrderDetail\Http\Middleware\OrderDetailMiddleware;

Route::get('/chi-tiet-don-hang/{tracking_id}', [OrderDetailController::class, 'index'])->middleware(OrderDetailMiddleware::class);
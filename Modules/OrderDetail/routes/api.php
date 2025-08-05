<?php

use Illuminate\Support\Facades\Route;
use Modules\OrderDetail\Http\Controllers\OrderDetailController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('orderdetails', OrderDetailController::class)->names('orderdetail');
});

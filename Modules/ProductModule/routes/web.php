<?php

use Illuminate\Support\Facades\Route;
use Modules\ProductModule\Http\Controllers\ProductModuleController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('productmodules', ProductModuleController::class)->names('productmodule');
});

Route::get('/list', [ProductModuleController::class, 'index']);

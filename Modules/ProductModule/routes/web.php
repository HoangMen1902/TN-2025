<?php

use Illuminate\Support\Facades\Route;
use Modules\ProductModule\Http\Controllers\ProductModuleController;
use Modules\ProductModule\Livewire\ProductList;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('productmodules', ProductModuleController::class)->names('productmodule');
});

Route::get('/san-pham', [ProductModuleController::class, 'index'])->name('store');

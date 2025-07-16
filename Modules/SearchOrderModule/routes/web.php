<?php

use Illuminate\Support\Facades\Route;
use Modules\SearchOrderModule\Http\Controllers\SearchOrderModuleController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('searchordermodules', SearchOrderModuleController::class)->names('searchordermodule');
});

    Route::get('/tra-cuu-don', [SearchOrderModuleController::class, 'index'])->name('tracking.index');

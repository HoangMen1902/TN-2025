<?php

use Illuminate\Support\Facades\Route;
use Modules\AboutModule\Http\Controllers\AboutModuleController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('aboutmodules', AboutModuleController::class)->names('aboutmodule');
});

Route::get('/gioi-thieu', [AboutModuleController::class, 'index'])->name('aboutmodule.index');

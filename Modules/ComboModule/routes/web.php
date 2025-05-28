<?php

use Illuminate\Support\Facades\Route;
use Modules\ComboModule\Http\Controllers\ComboModuleController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('combomodules', ComboModuleController::class)->names('combomodule');
});
Route::get('/combo', [ComboModuleController::class, 'index'])->name('combo.index');

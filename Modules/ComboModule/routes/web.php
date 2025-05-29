<?php

use Illuminate\Support\Facades\Route;
use Modules\ComboModule\Http\Controllers\ComboModuleController;
use Modules\DetailModule\Http\Controllers\DetailModuleController;
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('combomodules', ComboModuleController::class)->names('combomodule');
});
Route::get('/combo', [ComboModuleController::class, 'index'])->name('combo.index');
Route::get('/chi-tiet-combo/{slug}', [DetailModuleController::class, 'combo'])->name('combos.show');
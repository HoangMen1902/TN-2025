<?php

use Illuminate\Support\Facades\Route;
use Modules\CartModule\Http\Controllers\CartModuleController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('cartmodules', CartModuleController::class)->names('cartmodule');
});
Route::get('/gio-hang', [CartModuleController::class , 'index']);

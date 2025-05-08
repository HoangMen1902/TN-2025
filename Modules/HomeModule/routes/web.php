<?php

use Illuminate\Support\Facades\Route;
use Modules\HomeModule\Http\Controllers\HomeModuleController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('homemodules', HomeModuleController::class)->names('homemodule');
});
Route::get('/',[HomeModuleController::class ,'index']);
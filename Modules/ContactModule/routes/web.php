<?php

use Illuminate\Support\Facades\Route;
use Modules\ContactModule\Http\Controllers\ContactModuleController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('contactmodules', ContactModuleController::class)->names('contactmodule');
});

Route::get('/lien-he', [ContactModuleController::class , 'index'])->name('contactmodule.index');
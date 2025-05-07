<?php

use Illuminate\Support\Facades\Route;
use Modules\DetailModule\Http\Controllers\DetailModuleController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('detailmodules', DetailModuleController::class)->names('detailmodule');
});

Route::get('/chi-tiet', [DetailModuleController::class, 'index']);
<?php

use Illuminate\Support\Facades\Route;
use Modules\SuggestModule\Http\Controllers\SuggestModuleController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('suggestmodules', SuggestModuleController::class)->names('suggestmodule');
});

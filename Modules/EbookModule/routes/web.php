<?php

use Illuminate\Support\Facades\Route;
use Modules\EbookModule\Http\Controllers\EbookModuleController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('ebookmodules', EbookModuleController::class)->names('ebookmodule');
});
Route::get('/ebooks/{id}', [EbookModuleController::class, 'show']);

<?php

use Illuminate\Support\Facades\Route;
use Modules\CategoryModule\Http\Controllers\CategoryModuleController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('categorymodules', CategoryModuleController::class)->names('categorymodule');
});
 
Route::get('/san-pham-danh-muc/{categorySlug}', [CategoryModuleController::class, 'index'])->name('store-category');

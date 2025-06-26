<?php

use Illuminate\Support\Facades\Route;
use Modules\ImageSearch\Http\Controllers\ImageSearchController;



Route::get('/tim-kiem-hinh-anh', [ImageSearchController::class, 'index'])->name('imageSearch');
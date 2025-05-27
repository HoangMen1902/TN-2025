<?php

use Illuminate\Support\Facades\Route;
use Modules\DetailModule\Http\Controllers\DetailModuleController;



// Route::get('/chi-tiet/combo/{id}', [DetailModuleController::class, 'index']);
Route::get('/chi-tiet/{slug}', [DetailModuleController::class, 'index']);
Route::get('/chi-tiet-combo/{slug}', [DetailModuleController::class, 'combo']);
<?php

use Illuminate\Support\Facades\Route;
use Modules\CartModule\Http\Controllers\CartModuleController;
use Modules\CartModule\app\Livewire\Cart;
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('cartmodules', CartModuleController::class)->names('cartmodule');
});



    Route::get('/gio-hang', [CartModuleController::class, 'index'])->name('cart.index');
    Route::post('/add', [CartModuleController::class, 'addToCart'])->name('cart.add');
     





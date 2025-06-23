<?php

use Illuminate\Support\Facades\Route;
use Modules\MiniGameModule\App\Livewire\MiniGame;
use Modules\MiniGameModule\Http\Controllers\MiniGameModuleController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('minigamemodules', MiniGameModuleController::class)->names('minigamemodule');
});
Route::get('/mini-game', [MiniGameModuleController::class, 'index']);
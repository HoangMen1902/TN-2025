<?php

use Illuminate\Support\Facades\Route;
use Modules\MiniGame\Http\Controllers\MiniGameController;



Route::get('/mini-game', [MiniGameController::class, 'index']);

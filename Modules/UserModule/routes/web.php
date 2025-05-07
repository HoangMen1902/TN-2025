<?php
use Livewire\Livewire;
use Illuminate\Support\Facades\Route;
use Modules\UserModule\Http\Controllers\UserModuleController;
use Modules\UserModule\App\Http\Livewire\Login;
use Modules\UserModule\App\Http\Livewire\Register;
use Modules\UserModule\App\Http\Livewire\ForgotPassword;
use Modules\UserModule\Http\Controllers\AuthController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('usermodules', UserModuleController::class)->names('usermodule');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/register',[AuthController::class, 'showRegisterForm'])->name('register');
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('forgot-password');
Route::get('/change-forgot-password', [AuthController::class, 'showChangeForgotPasswordForm'])->name('change-forgot-password');
Route::get('/change-password', [AuthController::class, 'showChangePasswordForm'])->name('change-password');
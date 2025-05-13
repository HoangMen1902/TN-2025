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

Route::get('/dang-nhap', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/dang-ky',[AuthController::class, 'showRegisterForm'])->name('register');
Route::get('/quen-mat-khau', [AuthController::class, 'showForgotPasswordForm'])->name('forgot-password');
Route::get('/cap-nhat-mat-khau', [AuthController::class, 'showChangeForgotPasswordForm'])->name('change-forgot-password');
Route::get('/doi-mat-khau', [AuthController::class, 'showChangePasswordForm'])->name('change-password');
Route::get('/ho-so',[AuthController::class, 'showProfileInfomation'])->name('infomation');
Route::get('/dia-chi',[AuthController::class, 'showAddressInfomation'])->name('address');

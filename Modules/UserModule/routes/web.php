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

Route::get('/dang-nhap', [AuthController::class, 'showLoginForm'])->name('show.login');
Route::post('/dang-nhap', [AuthController::class, 'login'])->name('login');
Route::get('/dang-ky',[AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/dang-ky', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/quen-mat-khau', [AuthController::class, 'showForgotPasswordForm'])->name('forgot-password');
Route::get('/cap-nhat-mat-khau', [AuthController::class, 'showChangeForgotPasswordForm'])->name('change-forgot-password');
Route::get('/doi-mat-khau', [AuthController::class, 'showChangePasswordForm'])->name('change-password');
Route::get('/ho-so',[AuthController::class, 'showProfileInfomation'])->name('infomation');
Route::get('/dia-chi',[AuthController::class, 'showAddressInfomation'])->name('address');
Route::get('/don-hang',[AuthController::class, 'showOrderInfomation'])->name('order');
Route::get('/thong-bao',[AuthController::class, 'showNotification'])->name('notification');
Route::get('/san-pham-yeu-thich',[AuthController::class, 'showWishList'])->name('wishlist');
Route::post('/san-pham-yeu-thich',[AuthController::class, 'wishListRemove'])->name('wishlist.remove');
Route::get('/ma-giam-gia',[AuthController::class, 'voucherList'])->name('voucherlist');
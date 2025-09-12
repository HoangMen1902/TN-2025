<?php

use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;


Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});



Route::get('/orders/{order}/print', function (Order $order) {
    return Pdf::loadView('filament.order-detail-pdf', ['order' => $order])
        ->download('order-' . $order->id . '.pdf');
})->name('orders.print');

require __DIR__ . '/auth.php';

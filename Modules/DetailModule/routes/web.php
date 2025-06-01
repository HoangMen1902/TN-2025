<?php

use Illuminate\Support\Facades\Route;
use Modules\DetailModule\Http\Controllers\DetailModuleController;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;


// Route::get('/chi-tiet/combo/{id}', [DetailModuleController::class, 'index']);
Route::get('/chi-tiet/{slug}', [DetailModuleController::class, 'index']);
Route::get('/chi-tiet-combo/{slug}', [DetailModuleController::class, 'combo']);
Route::get('/product/{id}/preview', function ($id) {
    $product = Product::with('preview')->findOrFail($id);

    if (!$product->preview) abort(404, 'Preview not found');

    $filePath = $product->preview->file_path;

    if (!Storage::disk('public')->exists($filePath)) abort(404, 'File not found');

    return response()->file(Storage::disk('public')->path($filePath), [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="preview.pdf"',
    ]);
})->name('product.preview');



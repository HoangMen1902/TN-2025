<?php


use Illuminate\Support\Facades\Route;
use Modules\EbookModule\Http\Controllers\EbookModuleController;
use Modules\EbookModule\Http\Controllers\EpubSplitController;
use Modules\EbookModule\Http\Controllers\PdfSplitController;

// Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/ebooks', [EbookModuleController::class, 'index'])->name('ebooks.index');
    Route::get('/ebooks/{ebookId}', [EbookModuleController::class, 'show'])->name('ebooks.show');
    Route::get('/split-pdf/{ebookId}', [PdfSplitController::class, 'splitPdf'])->name('split-pdf');
    Route::get('/split-epub/{ebookId}', [EpubSplitController::class, 'splitEpub'])->name('split-epub');
    Route::post('/ebooks/{chapter}/toggle-read', [EbookModuleController::class, 'toggleRead'])->name('ebooks.toggleRead');
    Route::post('/ebooks/{chapter}/toggle-favorite', [EbookModuleController::class, 'toggleFavorite'])->name('ebooks.toggleFavorite');
    Route::middleware('auth')->post('/ebooks/{ebook}/update-position', [EbookModuleController::class, 'updatePosition']);
    Route::post('/ebook-audio-jobs/{id}/process', [PdfSplitController::class, 'processJob']);

    // });
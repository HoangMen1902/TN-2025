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
// });
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ManhwaController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\Admin\AdminController;

// Home route
Route::get('/', [HomeController::class, 'index'])->name('home');

// Manhwa routes
Route::get('/manhwa', [ManhwaController::class, 'index'])->name('manhwas.index');
Route::get('/manhwa/{manhwa}', [ManhwaController::class, 'show'])->name('manhwas.show');

// Genre routes
Route::get('/genres', [GenreController::class, 'index'])->name('genres.index');
Route::get('/genres/{genre}', [GenreController::class, 'show'])->name('genres.show');

// Chapter routes
Route::get('/manhwa/{manhwa}/chapter/{chapter}', [ChapterController::class, 'show'])
    ->name('chapters.show');

// Admin routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    
    // Manhwa management
    Route::get('/manhwa/create', [AdminController::class, 'createManhwa'])->name('manhwa.create');
    Route::post('/manhwa', [AdminController::class, 'storeManhwa'])->name('manhwa.store');
    Route::delete('/manhwa/{manhwa}', [AdminController::class, 'deleteManhwa'])->name('manhwa.delete');
    
    // Chapter management
    Route::get('/manhwa/{manhwa}/chapter/create', [AdminController::class, 'createChapter'])->name('chapter.create');
    Route::post('/manhwa/{manhwa}/chapter', [AdminController::class, 'storeChapter'])->name('chapter.store');
    Route::post('/manhwa/{manhwa}/chapter/zip', [AdminController::class, 'uploadChapterZip'])->name('chapter.upload-zip');
    Route::delete('/manhwa/{manhwa}/chapter/{chapter}', [AdminController::class, 'deleteChapter'])->name('chapter.delete');
});

<?php

use App\Http\Controllers\SeasonsController;
use App\Http\Controllers\SeriesController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Rotas para embed solutions
Route::prefix('api/embed')->group(function () {
    Route::post('/proxy', [\App\Http\Controllers\EmbedController::class, 'proxy'])->name('embed.proxy');
    Route::post('/metadata', [\App\Http\Controllers\EmbedController::class, 'fetchMetadata'])->name('embed.metadata');
    Route::post('/validate', [\App\Http\Controllers\EmbedController::class, 'validateUrl'])->name('embed.validate');
});

// Rotas para links salvos
Route::prefix('api/saved-links')->group(function () {
    Route::get('/', [\App\Http\Controllers\SavedLinkController::class, 'index'])->name('saved-links.index');
    Route::post('/', [\App\Http\Controllers\SavedLinkController::class, 'store'])->name('saved-links.store');
    Route::get('/{id}', [\App\Http\Controllers\SavedLinkController::class, 'show'])->name('saved-links.show');
    Route::delete('/{id}', [\App\Http\Controllers\SavedLinkController::class, 'destroy'])->name('saved-links.destroy');
});

Route::prefix('/series')->name('series.')->group(function () {
    Route::get('/', [SeriesController::class, 'index'])->name('index');
    Route::get('/criar', [SeriesController::class, 'create'])->name('create');
    Route::post('/', [SeriesController::class, 'store'])->name('store');
    Route::get('/{id}', [SeriesController::class, 'show'])->name('show');
    Route::get('/{id}/editar', [SeriesController::class, 'edit'])->name('edit');
    Route::put('/{id}', [SeriesController::class, 'update'])->name('update');
    Route::delete('/{id}', [SeriesController::class, 'destroy'])->name('destroy');
    Route::prefix('/{serie_id}/temporadas')->name('seasons.')->group(function () {
        Route::get('/', [SeasonsController::class, 'index'])->name('index');
        Route::get('/criar', [SeasonsController::class, 'create'])->name('create');
        Route::post('/', [SeasonsController::class, 'store'])->name('store');
        Route::get('/{id}/editar', [SeasonsController::class, 'edit'])->name('edit');
        Route::put('/{id}', [SeasonsController::class, 'update'])->name('update');
        Route::delete('/{id}', [SeasonsController::class, 'destroy'])->name('destroy');
    });
});

<?php

use App\Http\Controllers\SeasonsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SeriesController;

Route::get('/', function () {
    return redirect()->route('series.index');
});

Route::prefix('/series')->group(function () {
    Route::get('/', [SeriesController::class, 'index'])
        ->name('series.index');

    Route::get('/criar', [SeriesController::class, 'create'])
        ->name('series.create');

    Route::post('/', [SeriesController::class, 'store'])
        ->name('series.store');

    Route::get('/{id}/editar', [SeriesController::class, 'edit'])
        ->name('series.edit');

    Route::put('/{id}', [SeriesController::class, 'update'])
        ->name('series.update');

    Route::delete('/{id}', [SeriesController::class, 'destroy'])
        ->name('series.destroy');

    Route::prefix("/{serie_id}/temporadas")->group(function () {
        Route::get('/', [SeasonsController::class, 'index'])
            ->name('seasons.index');
        Route::get('/criar', [SeasonsController::class, 'create'])
            ->name('seasons.create');
        Route::post('/', [SeasonsController::class, 'store'])
            ->name('seasons.store');
        Route::get('/{id}/editar', [SeasonsController::class, 'edit'])
            ->name('seasons.edit');
        Route::put('/{id}', [SeasonsController::class, 'update'])
            ->name('seasons.update');
        Route::put('/{id}', [SeasonsController::class, 'destroy'])
            ->name('seasons.destroy');
    });
});


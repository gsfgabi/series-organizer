<?php

use App\Http\Controllers\SeasonsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SeriesController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

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

    Route::get('/{id}', [SeriesController::class, 'show'])
        ->name('series.show');

    Route::prefix("/{serie_id}/temporadas")->group(function () {
        Route::get('/', [SeasonsController::class, 'index'])
            ->name('seasons.index');

        Route::get('/criar', [SeasonsController::class, 'create'])
            ->name('seasons.create');

        Route::post('/', [SeasonsController::class, 'store'])
            ->name('seasons.store');
    });
});

<?php

use App\Http\Controllers\Api\SeriesApiController;
use App\Http\Controllers\Api\SeasonsApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API Routes for Series
Route::prefix('v1')->group(function () {
    Route::apiResource('series', SeriesApiController::class);
    Route::apiResource('series.seasons', SeasonsApiController::class);
}); 
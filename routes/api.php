<?php

use App\Http\Controllers\TrainController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth:sanctum');

Route::delete('/stations/clear', [StationController::class, 'clearAllStations'])->name('stations.clear');

Route::apiResource('trains', TrainController::class);
Route::apiResource('stations', StationController::class);
Route::apiResource('routes', RouteController::class);
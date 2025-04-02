<?php

use App\Http\Controllers\RouteController;
use App\Http\Controllers\StationController;
use App\Models\Station;
use Illuminate\Support\Facades\Route;

Route::get('/', [RouteController::class,'index']);

Route::get('/index', [StationController::class, "index"])->name('index');

require __DIR__.'/auth.php';
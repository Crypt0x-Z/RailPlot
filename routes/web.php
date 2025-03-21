<?php

use App\Http\Controllers\StationController;
use App\Models\Station;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/index', [StationController::class, "index"])->name('index');

require __DIR__.'/auth.php';
<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('index', function () {return view('index');})->name('index');

require __DIR__.'/auth.php';

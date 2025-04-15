<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;

Route::middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/admin', function () {
        $users = User::all();
        return view('admin.dashboard', compact('users'));
    })->name('admin.dashboard');

    Route::delete('/admin/users/{id}', function ($id) {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.dashboard')->with('success', 'User deleted');
    })->name('admin.users.delete');
});



// Regular dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// Main station editor
Route::get('/', function () {
    return view('index');
})->middleware(['auth']);

// Remove profile/edit for now
Route::get('/profile', function () {
    return redirect('/');
})->name('profile.edit');

require __DIR__.'/auth.php';

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.index', compact('users'));
    }

    public function destroy(User $user)
    {
        if ($user->is_admin) {
            return redirect()->route('admin.index')->with('error', 'Cannot delete another admin.');
        }

        $user->delete();
        return redirect()->route('admin.index')->with('success', 'User deleted successfully.');
    }
}
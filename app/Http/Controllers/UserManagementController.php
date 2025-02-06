<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.create_user');
    }

    /**
     * Store a newly created user in the database.
     */
    public function store(Request $request)
    {
        // Validate form input
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'role' => 'required|in:player,admin',
            'password' => 'required|string|min:6',
        ]);

        // Create new user
        User::create([
            'name' => $request->input('name'),
            'username' => $request->input('username'),
            'role' => $request->input('role'),
            'password' => Hash::make($request->input('password')),
        ]);

        // Redirect back with success message
        return redirect()->route('admin.users.create')->with('success', 'User added successfully!');
    }
}
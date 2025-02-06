<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminRoleController extends Controller
{
    public function showRoleChoice() {
        return view('admin.role_choice');
    }

    public function chooseRole(Request $request) {
        $role = $request->input('role');

        if (!in_array($role, ['admin', 'player'])) {
            return redirect()->route('admin.role_choice')->withErrors('Invalid role selection.');
        }

        session(['chosen_role' => $role]);

        return ($role === 'admin') 
            ? redirect()->route('admin.dashboard') 
            : redirect()->route('player.dashboard');
    }
}

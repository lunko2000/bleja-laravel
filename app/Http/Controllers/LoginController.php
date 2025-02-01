<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function show(Request $request) {
        return view('login');
    }

    public function login(Request $request)
    {

        if (!Auth::attempt($request->only('email', 'password'))) {
            abort(403, 'Invalid credentials');
        }

        $user = User::where('email', $request->email)->first();

        return $user;
    }

}

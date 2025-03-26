<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PlayerController extends Controller
{
    /**
     * Display the player's dashboard.
     */
    public function dashboard()
    {
        return view('player.dashboard');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\MarioKartGame;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlayerStatsController extends Controller
{
    /**
     * Display the player's stats page.
     */
    public function index()
    {
        // Get the authenticated player
        $player = Auth::user();

        // Ensure the user is a player (not an admin or guest)
        if ($player->role !== 'player') {
            return redirect()->route('dashboard')->with('error', 'Only players can access the stats page.');
        }

        // Fetch total matches played (excluding guest matches)
        $totalMatches = MarioKartGame::where(function ($query) use ($player) {
            $query->where('player1', $player->id)
                  ->orWhere('player2', $player->id);
        })
        ->whereHas('playerOne', function ($query) {
            $query->where('role', '!=', 'guest');
        })
        ->whereHas('playerTwo', function ($query) {
            $query->where('role', '!=', 'guest');
        })
        ->where('status', 'completed')
        ->count();

        // Fetch wins (where the player is the winner)
        $wins = MarioKartGame::where('winner_id', $player->id)
            ->whereHas('playerOne', function ($query) {
                $query->where('role', '!=', 'guest');
            })
            ->whereHas('playerTwo', function ($query) {
                $query->where('role', '!=', 'guest');
            })
            ->where('status', 'completed')
            ->count();

        // Calculate losses
        $losses = $totalMatches - $wins;

        // Calculate win/loss ratio (avoid division by zero)
        $winLossRatio = $totalMatches > 0 ? round($wins / $totalMatches * 100, 2) : 0;

        // Pass the stats to the view
        return view('player.stats', compact('totalMatches', 'wins', 'losses', 'winLossRatio'));
    }
}
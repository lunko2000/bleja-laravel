<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MarioKartGame;
use App\Models\MarioKartCup;
use App\Models\MarioKartGameCup;
use Illuminate\Http\Request;

class MarioKartGameController extends Controller
{
    /**
     * Show the match creation form.
     */
    public function create()
    {
        $players = User::where('role', 'player')->get();
        return view('admin.create_match', compact('players'));
    }

    /**
     * Store match setup in session instead of database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'player1' => 'required|exists:users,id',
            'player2' => 'required|exists:users,id',
            'format' => 'required|in:bo1,bo3',
        ]);

        // ✅ Clear previous session data
        session()->forget(['match_setup', 'veto_history']);

        // ✅ Store new match settings in session
        $matchSetup = [
            'player1' => $request->player1,
            'player2' => $request->player2,
            'format' => $request->format,
            'created_by' => auth()->id(),
        ];
        session(['match_setup' => $matchSetup]);

        // ✅ If BO3, auto-ban one random cup
        if ($request->format === 'bo3') {
            $randomCup = MarioKartCup::inRandomOrder()->first(); // Pick a random cup
            if ($randomCup) {
                session(['veto_history' => [[
                    'cup_id' => $randomCup->id,
                    'cup_name' => $randomCup->name,
                    'type' => 'auto-ban',
                ]]]);
            }
        }

        return redirect()->route('admin.matches.veto')
                        ->with('success', 'Match setup stored! Proceed to veto.');
    }

    /**
     * Show the veto process page.
     */
    public function veto()
    {
        $matchSetup = session('match_setup');
        $player1 = User::find($matchSetup['player1'])->username ?? "Unknown Player";
        $player2 = User::find($matchSetup['player2'])->username ?? "Unknown Player";
        if (!$matchSetup) {
            return redirect()->route('admin.dashboard')->with('error', 'No match setup found.');
        }

        // Fetch all available cups
        $availableCups = MarioKartCup::all();

        // Determine veto order
        $isBo1 = $matchSetup['format'] === 'bo1';
        $vetoSteps = $isBo1
            ? ['ban', 'ban', 'ban', 'ban', 'ban', 'ban', 'ban']
            : ['auto-ban', 'ban', 'ban', 'pick', 'pick', 'ban', 'ban', 'decider'];

        // Track veto history
        $vetoHistory = session('veto_history', []);
        $bannedCupIds = array_column($vetoHistory, 'cup_id');
        $remainingCup = MarioKartCup::whereNotIn('id', $bannedCupIds)->first();

        // Determine current veto step
        $currentStepIndex = count($vetoHistory);
        $currentStep = $vetoSteps[$currentStepIndex] ?? null;
        $playerTurnId = $currentStepIndex % 2 === 0 ? $matchSetup['player1'] : $matchSetup['player2'];
        $playerTurn = User::find($playerTurnId)->username ?? "Unknown Player";
        $currentStepMessage = $currentStep === 'pick'
            ? "🎯 {$playerTurn} is picking a cup..."
            : "🚫 {$playerTurn} is banning a cup...";

        return view('admin.veto', compact('matchSetup', 'availableCups', 'vetoSteps', 'vetoHistory', 'currentStepMessage', 'player1', 'player2', 'remainingCup'));
    }


    /**
     * Process veto selections.
     */
    public function processVeto(Request $request)
    {
        $matchSetup = session('match_setup');
        if (!$matchSetup) {
            return redirect()->route('admin.dashboard')->with('error', 'No match setup found.');
        }

        $request->validate(['cup_id' => 'required|exists:mario_kart_cups,id']);

        $vetoHistory = session('veto_history', []);
        if (in_array($request->cup_id, array_column($vetoHistory, 'cup_id'))) {
            return redirect()->back()->with('error', 'This cup has already been selected.');
        }

        $isBo1 = $matchSetup['format'] === 'bo1';
        $vetoSteps = $isBo1
            ? ['ban', 'ban', 'ban', 'ban', 'ban', 'ban', 'ban']
            : ['auto-ban', 'ban', 'ban', 'pick', 'pick', 'ban', 'ban', 'decider'];

        $currentStepIndex = count($vetoHistory);
        $currentStep = $vetoSteps[$currentStepIndex] ?? null;

        if (!$currentStep) {
            return redirect()->route('admin.dashboard')->with('error', 'Veto process is already complete.');
        }

        $cup = MarioKartCup::find($request->cup_id);

        if (!$cup) {
            return redirect()->back()->with('error', 'Invalid cup selection.');
        }

        $vetoHistory[] = [
            'cup_id' => $cup->id,
            'cup_name' => $cup->name,
            'type' => $currentStep,
        ];

        // ✅ Check if we are at the last ban before the decider
        if ($matchSetup['format'] === 'bo3' && count($vetoHistory) === 7) {
            $bannedCupIds = array_column($vetoHistory, 'cup_id');
            $deciderCup = MarioKartCup::whereNotIn('id', $bannedCupIds)->first();

            if ($deciderCup) {
                $vetoHistory[] = [
                    'cup_id' => $deciderCup->id,
                    'cup_name' => $deciderCup->name,
                    'type' => 'decider',
                ];
            }
        }

        session(['veto_history' => $vetoHistory]);

        return redirect()->route('admin.matches.veto')
                        ->with('success', "Veto action '{$currentStep}' recorded successfully.");
    }

    public function startMatch()
    {
        $matchSetup = session('match_setup');
        $vetoHistory = session('veto_history', []);

        if (!$matchSetup || count(array_filter($vetoHistory, fn($v) => $v['type'] === 'pick' || $v['type'] === 'decider')) < 3) {
            return redirect()->route('admin.dashboard')->with('error', 'Match cannot be started. Veto process incomplete.');
        }

        // Create the game in the database
        $game = MarioKartGame::create([
            'player1' => $matchSetup['player1'],
            'player2' => $matchSetup['player2'],
            'format' => $matchSetup['format'],
            'created_by' => auth()->id(),
        ]);

        // Store only "picked" and "decider" cups
        foreach ($vetoHistory as $veto) {
            if ($veto['type'] === 'pick' || $veto['type'] === 'decider') {
                MarioKartGameCup::create([
                    'game_id' => $game->id,
                    'cup_id' => $veto['cup_id'],
                    'type' => $veto['type'],
                ]);
            }
        }

        // Clear session data
        session()->forget(['match_setup', 'veto_history']);

        return redirect()->route('admin.dashboard')->with('success', 'Match successfully started!');
    }
}
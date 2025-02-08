<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MarioKartGame;
use App\Models\MarioKartTrack;
use App\Models\MarioKartCup;
use App\Models\MarioKartGameCup;
use App\Models\MarioKartGameRace;
use Illuminate\Http\Request;

class MarioKartGameController extends Controller
{
    /**
     * Show the match creation form.
     */
    public function create()
    {
        $players = User::where('id', '!=', auth()->id())->get();
        return view('admin.create_match', compact('players'));
    }

    /**
     * Store match setup in session instead of database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'player1' => 'required|exists:users,id',
            'player2' => 'required|exists:users,id|different:player1',
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

        // ✅ Fix: Adjust conditions for BO1
        $bo1Ready = $matchSetup['format'] === 'bo1' && count($vetoHistory) === 7;
        $bo3Ready = $matchSetup['format'] === 'bo3' && count(array_filter($vetoHistory, fn($v) => $v['type'] === 'pick' || $v['type'] === 'decider')) === 3;

        if (!$matchSetup || (!$bo1Ready && !$bo3Ready)) {
            return redirect()->route('admin.dashboard')->with('error', 'Match cannot be started. Veto process incomplete.');
        }

        // ✅ Create the game in the database
        $game = MarioKartGame::create([
            'player1' => $matchSetup['player1'],
            'player2' => $matchSetup['player2'],
            'format' => $matchSetup['format'],
            'created_by' => auth()->id(),
            'status' => 'pending',
        ]);

        // ✅ Store veto results correctly for BO1 and BO3
        $selectedCups = [];
        foreach ($vetoHistory as $veto) {
            if ($matchSetup['format'] === 'bo3' && ($veto['type'] === 'pick' || $veto['type'] === 'decider')) {
                $selectedCups[] = MarioKartGameCup::create([
                    'game_id' => $game->id,
                    'cup_id' => $veto['cup_id'],
                    'type' => $veto['type'],
                ]);
            }
        }

        // ✅ Fix for BO1: Store the last remaining cup
        if ($matchSetup['format'] === 'bo1') {
            $bannedCupIds = array_column($vetoHistory, 'cup_id');
            $remainingCup = MarioKartCup::whereNotIn('id', $bannedCupIds)->first();

            if ($remainingCup) {
                $selectedCups[] = MarioKartGameCup::create([
                    'game_id' => $game->id,
                    'cup_id' => $remainingCup->id,
                    'type' => 'decider', // Use "decider" since it's the last cup
                ]);
            }
        }

        // ✅ Create races for selected cups (BO1 or BO3)
        foreach ($selectedCups as $gameCup) {
            $tracks = MarioKartTrack::where('track_cup', $gameCup->cup_id)->get();

            if ($tracks->count() === 4) { // Ensure exactly 4 tracks
                foreach ($tracks as $index => $track) {
                    MarioKartGameRace::create([
                        'game_id' => $game->id,
                        'cup_id' => $gameCup->id,
                        'track_id' => $track->id,
                        'race_number' => $index + 1, // 1 to 4
                        'placements' => json_encode([]), // Empty until race results are entered
                        'winner' => null, // Winner will be updated later
                    ]);
                }
            }
        }

        // ✅ Clear session data
        session()->forget(['match_setup', 'veto_history']);

        return redirect()->route('admin.dashboard')->with('success', 'Match successfully started!');
    }

    public function dashboard()
    {
        // Get the latest match with a "pending" status
        $currentMatch = MarioKartGame::where('status', 'pending')->latest()->first();

        return view('admin.dashboard', compact('currentMatch'));
    }

    public function showMatch(MarioKartGame $game)
    {
        // ✅ Eager-load winner, playerOne, and playerTwo relationships
        $game->load(['winner', 'playerOne', 'playerTwo']);

        // ✅ If the game is completed, calculate and show final results
        if ($game->status === 'completed') {
            $totalPoints = [$game->player1 => 0, $game->player2 => 0];
            $points = [1 => 15, 2 => 12, 3 => 10, 4 => 8, 5 => 7, 6 => 6, 7 => 5, 8 => 4, 9 => 3, 10 => 2, 11 => 1, 12 => 0];

            // ✅ Fetch all completed races
            $completedRaces = $game->races()->whereNotNull('winner')->get();
            foreach ($completedRaces as $race) {
                $placements = json_decode($race->placements, true);
                $totalPoints[$game->player1] += $points[$placements[$game->player1]] ?? 0;
                $totalPoints[$game->player2] += $points[$placements[$game->player2]] ?? 0;
            }

            return view('admin.match', compact('game', 'totalPoints'));
        }

        // ✅ Fetch the current cup and the next race
        $currentCup = $game->cups()->first();
        $currentRace = $game->races()->whereNull('winner')->orderBy('race_number')->first();

        // ✅ If no races remain, mark the game as completed and reload the page
        if (!$currentRace) {
            $game->status = 'completed';
            $game->save();
            return redirect()->route('admin.match.show', $game->id);
        }

        // ✅ Retrieve selected placements
        $placements = json_decode($currentRace->placements, true) ?? [];
        $selectedPlayer1 = $placements[$game->player1] ?? null;
        $selectedPlayer2 = $placements[$game->player2] ?? null;

        return view('admin.match', compact('game', 'currentCup', 'currentRace', 'selectedPlayer1', 'selectedPlayer2'));
    }

    public function submitRaceResults(Request $request, MarioKartGame $game, MarioKartGameRace $race)
    {
        $request->validate([
            'player1_placement' => 'required|integer|min:1|max:12',
            'player2_placement' => 'required|integer|min:1|max:12',
        ]);

        // Define point system
        $points = [1 => 15, 2 => 12, 3 => 10, 4 => 8, 5 => 7, 6 => 6, 7 => 5, 8 => 4, 9 => 3, 10 => 2, 11 => 1, 12 => 0];

        // Store the placements in JSON format
        $race->placements = json_encode([
            $game->player1 => $request->player1_placement,
            $game->player2 => $request->player2_placement,
        ]);
        
        // Determine winner of this race
        $race->winner = $request->player1_placement < $request->player2_placement ? $game->player1 : $game->player2;
        $race->save();

        // Check if all 4 races in this cup are completed
        $cup = $game->cups()->where('id', $race->cup_id)->first();
        $completedRaces = $game->races()->where('cup_id', $cup->id)->whereNotNull('winner')->get();

        if ($completedRaces->count() === 4) {
            // Calculate total points for each player
            $totalPoints = [$game->player1 => 0, $game->player2 => 0];

            foreach ($completedRaces as $completedRace) {
                $placements = json_decode($completedRace->placements, true);
                $totalPoints[$game->player1] += $points[$placements[$game->player1]];
                $totalPoints[$game->player2] += $points[$placements[$game->player2]];
            }

            // Determine cup winner
            $cupWinner = $totalPoints[$game->player1] > $totalPoints[$game->player2] ? $game->player1 : $game->player2;
            $cup->cup_winner = $cupWinner;
            $cup->save();

            // ✅ Update mario_kart_games table (Game Winner + Status)
            $game->winner_id = $cupWinner;
            $game->status = 'completed';
            $game->save();

            return redirect()->route('admin.match.show', $game->id)->with('success', 'Cup has been completed! Match is now finished.');
        }

        // Redirect to next race
        return redirect()->route('admin.match.show', $game->id)->with('success', 'Race results submitted!');
    }
}
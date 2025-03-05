<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminRoleController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\MarioKartGameController; // Import the new controller
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    return Auth::user()->role === 'admin' 
        ? redirect()->route('admin.role_choice') 
        : redirect()->route('player.dashboard');
});

// Role Selection for Admins
Route::middleware('auth')->group(function () {
    Route::get('/admin/role-choice', [AdminRoleController::class, 'showRoleChoice'])->name('admin.role_choice');
    Route::post('/admin/choose-role', [AdminRoleController::class, 'chooseRole'])->name('admin.choose_role');
    
    // User Management
    Route::get('/admin/users/create', [UserManagementController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users/store', [UserManagementController::class, 'store'])->name('admin.users.store');

    // Admin Dashboard (Displays Current Match)
    Route::get('/admin/dashboard', [MarioKartGameController::class, 'dashboard'])->name('admin.dashboard');

    // Player Dashboard
    Route::get('/player/dashboard', function () {
        return view('player.dashboard');
    })->name('player.dashboard');

    // Match Creation (Admin Only)
    Route::get('/admin/matches/create', [MarioKartGameController::class, 'create'])->name('admin.matches.create');
    Route::post('/admin/matches/store', [MarioKartGameController::class, 'store'])->name('admin.matches.store');

    // Cup Veto Process
    Route::get('/admin/matches/veto', [MarioKartGameController::class, 'veto'])->name('admin.matches.veto');
    Route::post('/admin/matches/veto/process', [MarioKartGameController::class, 'processVeto'])->name('admin.matches.veto.process');
    
    // ✅ Start Match Route (After veto completion)
    Route::post('/admin/matches/start', [MarioKartGameController::class, 'startMatch'])->name('admin.matches.start');

    // ✅ View Ongoing Match Page (Shows races & tracks)
    Route::get('/admin/match/{game}', [MarioKartGameController::class, 'showMatch'])->name('admin.match.show');

    // ✅ Process Race Results (Stores placements & moves to next race)
    Route::post('/admin/match/{game}/race/{race}', [MarioKartGameController::class, 'submitRaceResults'])->name('admin.match.race.submit');

    // ✅ Determine Match Winner
    Route::get('/admin/matches/result/{game}', [MarioKartGameController::class, 'determineWinner'])->name('admin.matches.result');
    Route::post('/admin/matches/result/{game}', [MarioKartGameController::class, 'submitWinner'])->name('admin.matches.submit');

    // Profile Routes (Users can update username & password)
    Route::get('/profile', [UserController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [UserController::class, 'update'])->name('profile.update');

    Route::get('/api/cups/{cup_id}/tracks', [MarioKartGameController::class, 'getTracksForCup']);
    Route::post('/admin/matches/randomize-veto', [MarioKartGameController::class, 'randomizeVeto'])->name('admin.matches.randomize');
});

require __DIR__.'/auth.php';
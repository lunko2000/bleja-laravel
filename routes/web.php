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
    Route::get('/admin/users/create', [UserManagementController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users/store', [UserManagementController::class, 'store'])->name('admin.users.store');

    // Dashboards (Now Protected with 'auth' Middleware)
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/player/dashboard', function () {
        return view('player.dashboard');
    })->name('player.dashboard');

    // Match Creation (Admin Only)
    Route::get('/admin/matches/create', [MarioKartGameController::class, 'create'])->name('admin.matches.create');
    Route::post('/admin/matches/store', [MarioKartGameController::class, 'store'])->name('admin.matches.store');

    // Cup Veto Routes
    Route::get('/admin/matches/veto', [MarioKartGameController::class, 'veto'])->name('admin.matches.veto');
    Route::post('/admin/matches/veto/process', [MarioKartGameController::class, 'processVeto'])->name('admin.matches.veto.process');
    
    // ✅ Add the new "Start Match" route here
    Route::post('/admin/matches/start', [MarioKartGameController::class, 'startMatch'])->name('admin.matches.start');

    // Profile Routes (Users can update username & password)
    Route::get('/profile', [UserController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [UserController::class, 'update'])->name('profile.update');
});

require __DIR__.'/auth.php';
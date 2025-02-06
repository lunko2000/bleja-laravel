<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminRoleController;
use App\Http\Controllers\UserManagementController;
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
    })->middleware(['auth'])->name('admin.dashboard');

    Route::get('/player/dashboard', function () {
        return view('player.dashboard');
    })->middleware(['auth'])->name('player.dashboard');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
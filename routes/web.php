<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;


Route::redirect('/', 'login');

Route::get('/login', [LoginController::class, 'show']);
Route::post('/login', [LoginController::class, 'login']);

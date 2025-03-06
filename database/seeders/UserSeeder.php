<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        if (!User::where('username', 'lunko2000')->exists()) {
            User::create([
                'name' => 'Milan',
                'username' => 'lunko2000',
                'password' => Hash::make(env('DEFAULT_ADMIN_PASSWORD', 'password')),
                'role' => 'admin',
            ]);
        }

        if (!User::where('username', 'guest')->exists()) {
            User::create([
                'name' => 'Guest User',
                'username' => 'guest',
                'password' => Hash::make('guestpassword'), // Simple password for now
                'role' => 'guest',
            ]);
        }
    }
}
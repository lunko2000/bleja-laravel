<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder {
    public function run(): void {
        User::create([
            'name' => 'Milan',
            'username' => 'lunko2000', // Change to username instead of email
            'password' => Hash::make(env('DEFAULT_ADMIN_PASSWORD', 'password')), // Hash the password
            'role' => 'admin',
        ]);
    }
}

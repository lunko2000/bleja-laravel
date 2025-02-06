<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder {
    public function run(): void {
        User::create([
            'name' => 'Milan',
            'username' => 'lunko', // Change to username instead of email
            'password' => Hash::make('password'), // Hash the password
            'role' => 'admin',
        ]);
    }
}

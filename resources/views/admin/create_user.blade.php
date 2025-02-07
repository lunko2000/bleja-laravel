@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto mt-10 p-6 bg-gray-800 text-white shadow-lg rounded-lg">
    <h2 class="text-2xl font-bold mb-4 text-center">➕ Add a New User</h2>

    <!-- Success Message -->
    @if (session('success'))
        <div class="mb-4 p-4 bg-green-600 text-white rounded-md text-center">
            ✅ {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-300">Full Name</label>
            <input type="text" name="name" id="name" 
                   class="block w-full mt-1 p-2 bg-gray-700 border border-gray-600 rounded-md focus:ring-blue-500 focus:border-blue-500" 
                   required>
        </div>

        <!-- Username -->
        <div>
            <label for="username" class="block text-sm font-medium text-gray-300">Username</label>
            <input type="text" name="username" id="username" 
                   class="block w-full mt-1 p-2 bg-gray-700 border border-gray-600 rounded-md focus:ring-blue-500 focus:border-blue-500" 
                   required>
        </div>

        <!-- Role Selection -->
        <div>
            <label for="role" class="block text-sm font-medium text-gray-300">Role</label>
            <select name="role" id="role" 
                    class="block w-full mt-1 p-2 bg-gray-700 border border-gray-600 rounded-md focus:ring-blue-500 focus:border-blue-500" 
                    required>
                <option value="player">Player</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-300">Password</label>
            <input type="password" name="password" id="password" 
                   class="block w-full mt-1 p-2 bg-gray-700 border border-gray-600 rounded-md focus:ring-blue-500 focus:border-blue-500" 
                   required>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-center mt-6">
            <button type="submit" 
                    class="bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-2 px-6 rounded-lg transition">
                ➕ Add User
            </button>
        </div>
    </form>
</div>
@endsection
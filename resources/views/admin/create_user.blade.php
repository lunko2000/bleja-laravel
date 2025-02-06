@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto mt-10 p-6 bg-white shadow-md rounded-md">
    <h2 class="text-2xl font-bold mb-4">Add a New User</h2>

    <!-- Success Message -->
    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            ✅ {{ session('success') }}
        </div>
    @endif
    
    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf

        <!-- Name -->
        <div class="mb-4">
            <label for="name" class="block font-medium text-sm text-gray-700">Full Name</label>
            <input type="text" name="name" id="name" class="block w-full mt-1 rounded-md shadow-sm border-gray-300" required>
        </div>

        <!-- Username -->
        <div class="mb-4">
            <label for="username" class="block font-medium text-sm text-gray-700">Username</label>
            <input type="text" name="username" id="username" class="block w-full mt-1 rounded-md shadow-sm border-gray-300" required>
        </div>

        <!-- Role Selection -->
        <div class="mb-4">
            <label for="role" class="block font-medium text-sm text-gray-700">Role</label>
            <select name="role" id="role" class="block w-full mt-1 rounded-md shadow-sm border-gray-300" required>
                <option value="player">Player</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <!-- Password -->
        <div class="mb-4">
            <label for="password" class="block font-medium text-sm text-gray-700">Password</label>
            <input type="password" name="password" id="password" class="block w-full mt-1 rounded-md shadow-sm border-gray-300" required>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                ➕ Add User
            </button>
        </div>
    </form>
</div>
@endsection
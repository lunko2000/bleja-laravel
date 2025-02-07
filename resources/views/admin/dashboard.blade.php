@extends('layouts.app')

@section('content')
<div class="flex flex-col items-center justify-center min-h-screen bg-gray-900 text-white">
    <div class="bg-gray-800 p-8 rounded-lg shadow-md w-full max-w-lg text-center">
        <h2 class="text-3xl font-bold mb-4">Admin Dashboard</h2>
        <p class="text-gray-400 mb-6">Welcome, Admin! Manage users and matches from here.</p>

        <!-- Buttons Container -->
        <div class="flex flex-col sm:flex-row gap-4">
            <!-- Add New User Button -->
            <a href="{{ route('admin.users.create') }}" 
               class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
                ➕ Add New User
            </a>

            <!-- Start New Match Button -->
            <a href="{{ route('admin.matches.create') }}" 
               class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
                🎮 Start New Match
            </a>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="flex flex-col items-center justify-center min-h-screen bg-gray-900 text-white">

    <!-- Admin Dashboard Section -->
    <div class="bg-gray-800 p-8 rounded-lg shadow-md w-full max-w-lg text-center mb-6">
        <h2 class="text-3xl font-bold mb-4">Admin Dashboard</h2>
        <p class="text-gray-400 mb-6">Welcome, Admin! Manage users and matches from here.</p>

        <!-- Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('admin.users.create') }}" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition">
                ➕ Add New User
            </a>
            <!-- Start New Match Button (Disable if a match is pending) -->
            <a href="{{ route('admin.matches.create') }}" 
                class="w-full sm:w-auto font-bold py-3 px-6 rounded-lg transition
                {{ $currentMatch ? 'bg-gray-500 text-gray-300 cursor-not-allowed' : 'bg-green-600 hover:bg-green-700 text-white' }}"
                {{ $currentMatch ? 'disabled' : '' }}>
                🎮 Start New Match
            </a>
        </div>
    </div>

    <!-- Current Match Section (Separate from Admin Dashboard) -->
    <div class="bg-gray-800 p-8 rounded-lg shadow-md w-full max-w-lg text-center">
        <h2 class="text-2xl font-semibold mb-4">⚔️ Current Match</h2>

        @if($currentMatch)
            <p class="text-lg text-gray-300 mb-2">
                {{ $currentMatch->playerOne->username ?? 'Unknown' }} 
                vs 
                {{ $currentMatch->playerTwo->username ?? 'Unknown' }}
            </p>
            <p class="text-sm text-gray-400 mb-4">Format: <strong>{{ strtoupper($currentMatch->format) }}</strong></p>

            <!-- Guest Warning -->
            @if($currentMatch->playerOne->role === 'guest' || $currentMatch->playerTwo->role === 'guest')
                <p class="text-sm text-yellow-400 mb-4">
                    ⚠️ Games with guests do not track statistics.
                </p>
            @endif

            <a href="{{ route('admin.match.show', $currentMatch->id) }}" 
                class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-6 rounded-lg transition">
                🏆 Go to Match Page
            </a>
        @else
            <p class="text-gray-400 mb-4">No active matches at the moment.</p>
            <button class="bg-gray-500 text-gray-300 font-bold py-2 px-6 rounded-lg cursor-not-allowed" disabled>
                🏆 Go to Match Page
            </button>
        @endif
    </div>
</div>
@endsection
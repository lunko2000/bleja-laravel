@extends('layouts.app')

@section('content')
<div class="flex flex-col items-center justify-center min-h-screen bg-gray-900 text-white">
    <!-- Stats Section -->
    <div class="bg-gray-800 p-8 rounded-lg shadow-md w-full max-w-lg text-center">
        <h2 class="text-3xl font-bold mb-6">📊 Your Stats</h2>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <!-- Total Matches Played -->
            <div class="bg-gray-700 p-4 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-300 mb-2">Total Matches Played</h3>
                <p class="text-3xl font-bold text-blue-400">{{ $totalMatches }}</p>
            </div>

            <!-- Wins -->
            <div class="bg-gray-700 p-4 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-300 mb-2">Wins</h3>
                <p class="text-3xl font-bold text-green-400">{{ $wins }}</p>
            </div>

            <!-- Losses -->
            <div class="bg-gray-700 p-4 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-300 mb-2">Losses</h3>
                <p class="text-3xl font-bold text-red-400">{{ $losses }}</p>
            </div>
        </div>

        <!-- Win/Loss Bar -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-300 mb-2">Win/Loss</h3>
            @if($totalMatches > 0)
                <div class="flex items-center justify-center space-x-4">
                    <!-- Wins Label -->
                    <span class="text-green-400 font-bold">{{ $wins }}</span>

                    <!-- Bar -->
                    <div class="flex w-full max-w-xs h-6 bg-gray-500 rounded overflow-hidden">
                        <div class="bg-blue-600 h-full" style="width: {{ ($wins / $totalMatches) * 100 }}%;"></div>
                        <div class="bg-gray-400 h-full" style="width: {{ ($losses / $totalMatches) * 100 }}%;"></div>
                    </div>

                    <!-- Losses Label -->
                    <span class="text-red-400 font-bold">{{ $losses }}</span>
                </div>
                <!-- Win/Loss Ratio Percentage -->
                <p class="text-sm text-yellow-400 mt-2">{{ $winLossRatio }}%</p>
            @else
                <p class="text-gray-400">No matches played yet.</p>
            @endif
        </div>

        <!-- Back to Dashboard Button -->
        <a href="{{ route('player.dashboard') }}" class="mt-6 inline-block bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded-lg transition">
            🔙 Back to Dashboard
        </a>
    </div>
</div>
@endsection
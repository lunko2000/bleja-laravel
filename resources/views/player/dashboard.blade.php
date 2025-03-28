@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto mt-10 p-6 bg-gray-800 text-white shadow-md rounded-md text-center">
    <h2 class="text-2xl font-bold mb-4">🎮 Player Dashboard</h2>
    <p class="mb-6">Welcome, {{ auth()->user()->username }}!</p>

    <!-- Button to View Stats -->
    <a href="{{ route('player.stats') }}" class="bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-2 px-6 rounded-lg transition">
        📊 View My Stats
    </a>
</div>
@endsection
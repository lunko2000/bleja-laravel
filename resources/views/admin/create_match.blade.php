@extends('layouts.app')

@section('content')
<div class="flex flex-col items-center justify-center min-h-screen bg-gray-900 text-white">
    <div class="bg-gray-800 p-8 rounded-lg shadow-md w-full max-w-lg text-center">
        <h2 class="text-3xl font-bold mb-4">🎮 Start New Match</h2>

        <!-- Match Creation Form -->
        <form method="POST" action="{{ route('admin.matches.store') }}">
            @csrf

            <!-- Select Player 1 -->
            <div class="mb-4">
                <label for="player1" class="block text-sm font-medium text-gray-300">Select Player 1</label>
                <select name="player1" id="player1" required 
                        class="block w-full mt-1 p-2 bg-gray-700 border border-gray-600 rounded-md">
                    <option value="">-- Choose a Player --</option>
                        @foreach ($players as $player)
                            @if ($player->id !== auth()->id()) 
                                <option value="{{ $player->id }}">{{ $player->username }}</option>
                            @endif
                        @endforeach
                </select>
            </div>

            <!-- Select Player 2 -->
            <div class="mb-4">
                <label for="player2" class="block text-sm font-medium text-gray-300">Select Player 2</label>
                <select name="player2" id="player2" required 
                        class="block w-full mt-1 p-2 bg-gray-700 border border-gray-600 rounded-md">
                    <option value="">-- Choose a Player --</option>
                    @foreach ($players as $player)
                        <option value="{{ $player->id }}">{{ $player->username }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Select Match Format -->
            <div class="mb-4">
                <label for="format" class="block text-sm font-medium text-gray-300">Match Format</label>
                <select name="format" id="format" required 
                        class="block w-full mt-1 p-2 bg-gray-700 border border-gray-600 rounded-md">
                    <option value="bo1">Best of 1</option>
                    <option value="bo3">Best of 3</option>
                </select>
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                    class="w-full bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-3 px-6 rounded-lg transition">
                🚀 Create Match
            </button>
        </form>
    </div>
</div>
@endsection

<script>
document.addEventListener("DOMContentLoaded", function () {
    const player1 = document.getElementById("player1");
    const player2 = document.getElementById("player2");

    function updatePlayer2Options() {
        const selectedPlayer1 = player1.value;
        for (let option of player2.options) {
            option.disabled = option.value === selectedPlayer1 && option.value !== "";
        }
    }

    player1.addEventListener("change", updatePlayer2Options);
    player2.addEventListener("change", updatePlayer2Options);
});
</script>
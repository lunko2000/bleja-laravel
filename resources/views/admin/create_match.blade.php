@extends('layouts.app')

@section('content')
<div class="flex flex-col items-center justify-center min-h-screen bg-gray-900 text-white">
    <div class="bg-gray-800 p-8 rounded-lg shadow-md w-full max-w-lg text-center">
        <h2 class="text-3xl font-bold mb-4">🎮 Start New Match</h2>

        <!-- Match Creation Form -->
        <form method="POST" action="{{ route('admin.matches.store') }}" id="matchForm">
            @csrf

            <!-- Select Player 1 -->
            <div class="mb-4">
                <label for="player1" class="block text-sm font-medium text-gray-300">Select Player 1</label>
                <select name="player1" id="player1" required 
                        class="block w-full mt-1 p-2 bg-gray-700 border border-gray-600 rounded-md">
                    <option value="">-- Choose a Player --</option>
                    @foreach ($players as $player)
                        @if ($player->id !== auth()->id())
                            <option value="{{ $player->id }}" data-role="{{ $player->role }}">{{ $player->username }}</option>
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
                        <option value="{{ $player->id }}" data-role="{{ $player->role }}">{{ $player->username }}</option>
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

            <!-- Guest Warning (Hidden by Default) -->
            <p id="guestWarning" class="mt-4 text-sm text-yellow-400 hidden">
                ⚠️ Games with guests do not track statistics.
            </p>
        </form>
    </div>
</div>
@endsection

<script>
document.addEventListener("DOMContentLoaded", function () {
    const player1 = document.getElementById("player1");
    const player2 = document.getElementById("player2");
    const guestWarning = document.getElementById("guestWarning");

    function updatePlayerOptions() {
        const selectedPlayer1 = player1.value;
        const selectedPlayer2 = player2.value;

        // Disable Player 1 in Player 2 dropdown and vice versa
        for (let option of player2.options) {
            option.disabled = option.value === selectedPlayer1 && option.value !== "";
        }
        for (let option of player1.options) {
            option.disabled = option.value === selectedPlayer2 && option.value !== "";
        }

        // Show warning if either player is a guest
        const isGuestSelected = 
            (player1.value && player1.options[player1.selectedIndex].getAttribute('data-role') === 'guest') ||
            (player2.value && player2.options[player2.selectedIndex].getAttribute('data-role') === 'guest');
        guestWarning.classList.toggle('hidden', !isGuestSelected);
    }

    player1.addEventListener("change", updatePlayerOptions);
    player2.addEventListener("change", updatePlayerOptions);

    // Initial check when page loads
    updatePlayerOptions();
});
</script>
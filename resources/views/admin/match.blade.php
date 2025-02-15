@extends('layouts.app')

@section('content')
<div class="flex flex-col items-center justify-center min-h-screen bg-gray-900 text-white">
    <div class="bg-gray-800 p-8 rounded-lg shadow-md w-full max-w-3xl text-center">
        <h2 class="text-3xl font-bold mb-4">🏁 Match Progress</h2>

        <!-- Match Information with BO3 Cup Wins -->
        <div class="flex flex-col items-center justify-center mb-6">
            <!-- Current Cup Section -->
            <h4 class="text-lg font-bold text-gray-400 uppercase mb-1">Current Cup</h4>
            
            <div class="flex items-center space-x-3">
                <!-- Cup Logo -->
                @if(isset($currentCup) && $currentCup)
                    <img src="{{ asset('storage/cups/' . $currentCup->cup->cup_logo) }}" 
                        alt="{{ $currentCup->cup->name }}" class="w-10 h-10 rounded-full shadow-md">
                @endif

                <!-- Cup Name -->
                @if(isset($currentCup) && $currentCup)
                    <h4 class="text-xl font-semibold text-blue-400">{{ $currentCup->cup->name }}</h4>
                @endif

                <!-- Picked By (if applicable) -->
                @if(isset($currentCup) && $currentCup)
                    @if($currentCup->type === 'pick' && $currentCup->picker)
                        <p class="text-sm text-gray-300">({{ $currentCup->picker->username }})</p>
                    @elseif($currentCup->type === 'decider')
                        <p class="text-sm text-yellow-400">(Decider Cup)</p>
                    @endif
                @endif
            </div>
        </div>

        <!-- Player Names & Cup Wins -->
        <div class="flex items-center justify-center mb-4">
            <div class="flex flex-col items-center">
                <p class="text-lg font-semibold text-white">{{ $game->playerOne->username }}</p>
                <div class="flex space-x-1">
                    @for ($i = 1; $i <= 2; $i++)
                        <div class="w-4 h-4 rounded-full border-2 border-white {{ $player1CupWins >= $i ? 'bg-green-500' : 'bg-gray-700' }}"></div>
                    @endfor
                </div>
            </div>
            
            <!-- Centered VS -->
            <p class="text-2xl font-bold text-yellow-400 mx-12">VS</p>

            <div class="flex flex-col items-center">
                <p class="text-lg font-semibold text-white">{{ $game->playerTwo->username }}</p>
                <div class="flex space-x-1">
                    @for ($i = 1; $i <= 2; $i++)
                        <div class="w-4 h-4 rounded-full border-2 border-white {{ $player2CupWins >= $i ? 'bg-green-500' : 'bg-gray-700' }}"></div>
                    @endfor
                </div>
            </div>
        </div>

        <!-- Match Format -->
        <p class="text-gray-300 mb-4 text-lg">Format: <strong>{{ strtoupper($game->format) }}</strong></p>

        <!-- If Match is Completed, Show Final Results -->
        @if ($game->status === 'completed')
            <div class="bg-gray-700 p-6 rounded-lg">
                <h3 class="text-2xl font-bold text-green-400 mb-3">
                    🏆 Winner: {{ optional($game->winner)->username ?? 'Unknown' }}
                </h3>
                <p class="text-gray-300 text-lg">Final Score:</p>
                <p class="text-xl font-semibold text-yellow-300">
                    {{ $totalPoints[$game->player1] }} - {{ $totalPoints[$game->player2] }}
                </p>
                <a href="{{ route('admin.dashboard') }}" 
                   class="mt-4 inline-block bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded-lg transition">
                    🔙 Return to Admin Dashboard
                </a>
            </div>
        @else
            <!-- Current Race Info -->
            <div class="mb-6">
                <h4 class="text-2xl font-semibold text-blue-400">{{ $currentCup->name }}</h4>
                <p class="text-xl text-gray-300 font-bold">Race {{ $currentRace->race_number }}: {{ $currentRace->track->name }}</p>
            </div>

            <!-- Track Image & Layout -->
            <div class="flex justify-center items-center space-x-6 mb-6">
                <!-- Track Image (Larger) -->
                <img src="{{ asset('storage/tracks/' . $currentRace->track->track_image) }}" 
                    alt="Track Image" class="w-80 h-auto rounded-lg shadow-md">

                <!-- Divider Line -->
                <div class="w-px h-32 bg-gray-500"></div>

                <!-- Track Layout (Smaller) -->
                <img src="{{ asset('storage/track-layouts/' . $currentRace->track->track_layout) }}" 
                    alt="Track Layout" class="w-56 h-auto rounded-lg shadow-md">
            </div>

            <!-- Race Placements Form -->
            <form method="POST" action="{{ route('admin.match.race.submit', ['game' => $game->id, 'race' => $currentRace->id]) }}">
                @csrf
                
                <div class="flex flex-col items-center mb-4">
                    <!-- Player 1 Placements -->
                    <label class="block text-lg font-medium text-gray-300 mb-2">{{ $game->playerOne->username }}'s Placement</label>
                    <div class="flex justify-center space-x-2 flex-wrap mb-4">
                        @for ($i = 1; $i <= 12; $i++)
                            <button type="button" class="placement-button px-4 py-2 rounded bg-gray-700 hover:bg-green-500"
                                data-player="player1" data-value="{{ $i }}">
                                {{ $i }}
                            </button>
                        @endfor
                        <input type="hidden" name="player1_placement" id="player1_placement" value="">
                    </div>

                    <!-- Player 2 Placements -->
                    <label class="block text-lg font-medium text-gray-300 mb-2">{{ $game->playerTwo->username }}'s Placement</label>
                    <div class="flex justify-center space-x-2 flex-wrap">
                        @for ($i = 1; $i <= 12; $i++)
                            <button type="button" class="placement-button px-4 py-2 rounded bg-gray-700 hover:bg-green-500"
                                data-player="player2" data-value="{{ $i }}">
                                {{ $i }}
                            </button>
                        @endfor
                        <input type="hidden" name="player2_placement" id="player2_placement" value="">
                    </div>
                </div>
                
                <!-- Next Race Button -->
                <button type="submit" id="nextRaceBtn" class="w-full bg-gray-600 text-gray-300 font-bold py-3 px-6 rounded-lg transition cursor-not-allowed" disabled>
                    {{ $currentRace->race_number < 4 ? 'Next Race' : 'Finish Cup' }}
                </button>
            </form>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('.placement-button');
    let player1Selected = null;
    let player2Selected = null;

    buttons.forEach(button => {
        button.addEventListener('click', function () {
            const player = this.getAttribute('data-player');
            const value = this.getAttribute('data-value');

            if (player === 'player1') {
                // If player1 clicks the same button again, deselect it
                if (player1Selected === value) {
                    player1Selected = null;
                    document.getElementById('player1_placement').value = "";
                } else {
                    player1Selected = value;
                    document.getElementById('player1_placement').value = value;
                }
            } else {
                // If player2 clicks the same button again, deselect it
                if (player2Selected === value) {
                    player2Selected = null;
                    document.getElementById('player2_placement').value = "";
                } else {
                    player2Selected = value;
                    document.getElementById('player2_placement').value = value;
                }
            }

            // Reset all buttons
            buttons.forEach(btn => {
                btn.classList.remove('bg-green-500', 'text-white', 'opacity-50', 'cursor-not-allowed', 'no-hover');
                btn.classList.add('bg-gray-700', 'hover:bg-green-500');
                btn.disabled = false;
            });

            // Apply styles to selected buttons
            if (player1Selected) {
                buttons.forEach(btn => {
                    if (btn.getAttribute('data-player') === 'player1') {
                        btn.classList.add('opacity-50', 'cursor-not-allowed', 'no-hover');
                        btn.disabled = true;
                    }
                    if (btn.getAttribute('data-player') === 'player1' && btn.getAttribute('data-value') === player1Selected) {
                        btn.classList.remove('opacity-50', 'cursor-not-allowed', 'no-hover');
                        btn.classList.add('bg-green-500', 'text-white');
                        btn.disabled = false;
                    }
                    if (btn.getAttribute('data-player') === 'player2' && btn.getAttribute('data-value') === player1Selected) {
                        btn.classList.add('opacity-50', 'cursor-not-allowed', 'no-hover');
                        btn.disabled = true;
                    }
                });
            }

            if (player2Selected) {
                buttons.forEach(btn => {
                    if (btn.getAttribute('data-player') === 'player2') {
                        btn.classList.add('opacity-50', 'cursor-not-allowed', 'no-hover');
                        btn.disabled = true;
                    }
                    if (btn.getAttribute('data-player') === 'player2' && btn.getAttribute('data-value') === player2Selected) {
                        btn.classList.remove('opacity-50', 'cursor-not-allowed', 'no-hover');
                        btn.classList.add('bg-green-500', 'text-white');
                        btn.disabled = false;
                    }
                    if (btn.getAttribute('data-player') === 'player1' && btn.getAttribute('data-value') === player2Selected) {
                        btn.classList.add('opacity-50', 'cursor-not-allowed', 'no-hover');
                        btn.disabled = true;
                    }
                });
            }

            // Enable or disable "Next Race" button
            let nextRaceBtn = document.getElementById('nextRaceBtn');
            if (player1Selected && player2Selected) {
                nextRaceBtn.removeAttribute('disabled');
                nextRaceBtn.classList.remove('bg-gray-600', 'text-gray-300', 'cursor-not-allowed');
                nextRaceBtn.classList.add('bg-indigo-500', 'hover:bg-indigo-600', 'text-white');
            } else {
                nextRaceBtn.setAttribute('disabled', 'true');
                nextRaceBtn.classList.remove('bg-indigo-500', 'hover:bg-indigo-600', 'text-white');
                nextRaceBtn.classList.add('bg-gray-600', 'text-gray-300', 'cursor-not-allowed');
            }
        });
    });
});
</script>

<style>
.no-hover:hover {
    background-color: inherit !important;
}
</style>

@endsection
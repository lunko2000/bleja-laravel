@extends('layouts.app')

@section('content')
<div class="flex flex-col items-center justify-center min-h-screen bg-gray-900 text-white">
    <div class="bg-gray-800 p-8 rounded-lg shadow-md w-full max-w-2xl text-center">
        <h2 class="text-3xl font-bold mb-4">🏆 Cup Veto Process</h2>
        <p class="text-gray-400 mb-6">Follow the veto order to determine the played cups.</p>

        <!-- Match Information -->
        <h3 class="text-lg font-semibold mb-4">
            Current Match: {{ $player1 }} vs {{ $player2 }} 
            ({{ strtoupper($matchSetup['format']) }})
        </h3>

        <!-- Display Current Veto Step -->
        <h4 class="text-lg font-semibold text-yellow-400 mb-2">
            {{ $currentStepMessage }}
        </h4>

        <!-- Display Veto Order -->
        <div class="mb-6 text-left">
            <h4 class="text-indigo-400">Veto Order:</h4>
            <ul class="list-disc list-inside">
                @foreach ($vetoSteps as $index => $step)
                    <li>
                        @if(isset($vetoHistory[$index]['cup_name']))
                            <span class="
                                @if($vetoHistory[$index]['type'] === 'ban') text-red-400 
                                @elseif($vetoHistory[$index]['type'] === 'pick') text-green-400 
                                @elseif($vetoHistory[$index]['type'] === 'decider') text-blue-400 font-bold 
                                @endif">
                                {{ ucfirst($vetoHistory[$index]['type']) }}: 
                                {{ $vetoHistory[$index]['cup_name'] ?? 'Unknown Cup' }}

                                @if($matchSetup['format'] !== 'bo3' || !in_array($vetoHistory[$index]['type'], ['auto-ban', 'decider']))
                                    ({{ $vetoHistory[$index]['player_name'] ?? 'Unknown Player' }})
                                @endif

                                <img src="{{ asset('storage/cups/' . $vetoHistory[$index]['cup_logo']) }}" class="inline-block w-5 h-5 ml-2">
                            </span>
                        @else
                            <span class="text-gray-400">{{ ucfirst($step) }}...</span>
                        @endif
                    </li>
                @endforeach

                <!-- Show "Cup to be Played" in BO1 -->
                @if ($matchSetup['format'] === 'bo1' && count($vetoHistory) === 7 && isset($remainingCup))
                    <li>
                        <span class="text-blue-400 font-bold">
                            🏆 Cup to be Played: {{ $remainingCup->name }}
                            <img src="{{ asset('storage/cups/' . $remainingCup->cup_logo) }}" class="inline-block w-5 h-5 ml-2">
                        </span>
                    </li>
                @endif
            </ul>
        </div>

        <!-- Cup Selection Form -->
        @if($currentStepMessage !== 'Veto process complete!')
            <form method="POST" action="{{ route('admin.matches.veto.process') }}">
                @csrf

                <!-- Cup Selection Grid -->
                <div class="grid grid-cols-4 gap-4 mb-6">
                    @foreach ($availableCups as $cup)
                    @php
                    $isBannedOrPicked = in_array($cup->id, array_column($vetoHistory, 'cup_id'));
                    $isBo1FinalCup = $matchSetup['format'] === 'bo1' && count($vetoHistory) === 7 && isset($remainingCup) && $remainingCup->id === $cup->id;
                    $isDecider = $matchSetup['format'] === 'bo3' 
                        && in_array('decider', array_column($vetoHistory, 'type')) 
                        && $cup->id === $vetoHistory[array_search('decider', array_column($vetoHistory, 'type'))]['cup_id'];
                    @endphp
                    <button type="button"
                        class="cup-button relative bg-gray-700 hover:bg-gray-600 p-2 rounded-lg transition
                        @if($isBannedOrPicked) 
                            cursor-not-allowed opacity-50 no-hover
                            @if($vetoHistory[array_search($cup->id, array_column($vetoHistory, 'cup_id'))]['type'] === 'ban') bg-red-700 hover:bg-red-700
                            @elseif($vetoHistory[array_search($cup->id, array_column($vetoHistory, 'cup_id'))]['type'] === 'pick') bg-green-700 hover:bg-green-700
                            @endif 
                        @elseif($isBo1FinalCup)
                            bg-blue-700 text-white font-bold cursor-not-allowed no-hover
                        @elseif($isDecider)
                            bg-blue-700 text-white font-bold cursor-not-allowed no-hover !important
                        @endif"
                        data-cup-id="{{ $cup->id }}"
                        @if($isBannedOrPicked || $isBo1FinalCup || $isDecider) disabled @endif>
                        <img src="{{ asset('storage/cups/' . $cup->cup_logo) }}" alt="{{ $cup->name }}" class="w-16 h-16 mx-auto">
                        <span class="tooltip absolute top-0 left-0 w-full text-center bg-black text-white text-xs p-1 rounded opacity-0">
                            {{ $cup->name }}
                        </span>
                    </button>
                    @endforeach
                </div>

                <!-- Hidden Input for Selected Cup -->
                <input type="hidden" name="cup_id" id="selectedCupId" required>

                <!-- Confirm Veto Choice Button -->
                <button type="submit" id="confirmButton"
                        class="w-full bg-gray-500 text-gray-300 font-bold py-3 px-6 rounded-lg transition"
                        disabled>
                    ✅ Confirm Veto Choice
                </button>
            </form>
        @endif

        <!-- Start the Match Button -->
        @php
            $bo1Ready = $matchSetup['format'] === 'bo1' && count($vetoHistory) === 7;
            $bo3Ready = $matchSetup['format'] === 'bo3' && count(array_filter($vetoHistory, fn($v) => $v['type'] === 'pick' || $v['type'] === 'decider')) === 3;
            $isReady = $bo1Ready || $bo3Ready;
        @endphp

        <form method="POST" action="{{ route('admin.matches.start') }}">
            @csrf
            <button type="submit" 
                    class="w-full mt-4 font-bold py-3 px-6 rounded-lg transition
                    {{ $isReady ? 'bg-green-500 hover:bg-green-600 text-white' : 'bg-gray-500 text-gray-300 cursor-not-allowed' }}"
                    {{ $isReady ? '' : 'disabled' }}>
                🏁 Start the Match
            </button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    console.log("🔍 Checking for Decider Cup...");

    // Ensure that 'decider' exists in vetoHistory before proceeding
    const vetoHistory = @json($vetoHistory);
    const deciderEntry = vetoHistory.find(entry => entry.type === 'decider');

    if (deciderEntry) {
        const deciderCupId = deciderEntry.cup_id;
        console.log("🆔 Decider Cup ID:", deciderCupId);

        if (deciderCupId) {
            // Select the button with matching cup ID
            const deciderButton = document.querySelector(`[data-cup-id='${deciderCupId}']`);

            if (deciderButton) {
                console.log("✅ Decider Cup Button Found!", deciderButton);

                // Remove blue class from any previously assigned elements (fixes the auto-ban issue)
                document.querySelectorAll('.cup-button.bg-blue-700').forEach(btn => {
                    btn.classList.remove('bg-blue-700', 'text-white', 'font-bold');
                });

                // Apply blue class only to the actual decider cup
                deciderButton.classList.add('bg-blue-700', 'text-white', 'font-bold');
                console.log("🎨 Applied Decider Cup Class:", deciderButton.classList);
            } else {
                console.warn("⚠️ Decider Cup Button NOT Found in DOM!");
            }
        }
    } else {
        console.warn("⚠️ No Decider Cup Found in Veto History Yet!");
    }

    // ✅ Keep Your Existing Button Selection Logic
    const cupButtons = document.querySelectorAll('.cup-button:not(.no-hover)');
    const confirmButton = document.getElementById('confirmButton');
    const selectedCupInput = document.getElementById('selectedCupId');

    cupButtons.forEach(button => {
        button.addEventListener('click', function () {
            cupButtons.forEach(btn => btn.classList.remove('bg-green-500', 'text-white'));
            this.classList.add('bg-green-500', 'text-white');
            selectedCupInput.value = this.getAttribute('data-cup-id');
            confirmButton.removeAttribute('disabled');
            confirmButton.classList.add('bg-indigo-500', 'hover:bg-indigo-600', 'text-white');
        });
    });
});
</script>

<style>
.no-hover {
    pointer-events: none !important;
    cursor: not-allowed !important;
}
.tooltip {
    transition: opacity 0.3s ease;
}

.cup-button.bg-blue-700 {
    background-color: #1e40af !important; /* Force blue */
}
</style>

@endsection
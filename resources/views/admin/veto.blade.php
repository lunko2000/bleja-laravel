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

        <!-- Cup Selection Form -->
        @if($currentStepMessage !== 'Veto process complete!')
            <form method="POST" action="{{ route('admin.matches.veto.process') }}">
                @csrf

                <!-- Cup Selection Grid -->
                <div class="grid grid-cols-4 gap-4 mb-6">
                    @foreach ($availableCups as $cup)
                    @php
                        $isBannedOrPicked = in_array($cup->id, array_column($vetoHistory, 'cup_id'));
                        $cupVetoEntry = collect($vetoHistory)->firstWhere('cup_id', $cup->id);
                        $vetoType = $cupVetoEntry['type'] ?? null;
                        $vetoPlayer = $cupVetoEntry['player_name'] ?? null;

                        $isBo1FinalCup = $matchSetup['format'] === 'bo1' && count($vetoHistory) === 7 && isset($remainingCup) && $remainingCup->id === $cup->id;
                        $isDecider = $matchSetup['format'] === 'bo3' 
                            && in_array('decider', array_column($vetoHistory, 'type')) 
                            && $cup->id === $vetoHistory[array_search('decider', array_column($vetoHistory, 'type'))]['cup_id'];
                    @endphp

                    <div class="relative flex flex-col items-center">
                        <!-- Main Cup Button -->
                        <button type="button"
                            class="cup-button relative bg-gray-700 hover:bg-gray-600 p-2 rounded-lg transition w-20 h-20 md:w-36 md:h-20 flex items-center justify-center
                            @if($isBannedOrPicked) 
                                cursor-not-allowed opacity-50 no-hover
                                @if($vetoType === 'ban') bg-red-700 hover:bg-red-700
                                @elseif($vetoType === 'pick') bg-green-700 hover:bg-green-700
                                @endif 
                            @elseif($isBo1FinalCup)
                                bg-blue-700 text-white font-bold cursor-not-allowed no-hover
                            @elseif($isDecider)
                                bg-blue-700 text-white font-bold cursor-not-allowed no-hover !important
                            @endif"
                            data-cup-id="{{ $cup->id }}"
                            @if($isBannedOrPicked || $isBo1FinalCup || $isDecider) disabled @endif>
                            
                            <!-- Cup Logo (Centered) -->
                            <img src="{{ asset('storage/cups/' . $cup->cup_logo) }}" alt="{{ $cup->name }}" class="w-16 h-16 mx-auto">

                            <!-- Info Button (Inside the Cup Button) -->
                            <button class="absolute top-1 right-1 bg-blue-500 hover:bg-blue-600 text-white text-xs px-1 py-1 rounded-full"
                                onclick="showCupTracks(event, {{ $cup->id }})">
                                <i class="bi bi-info-circle"></i>
                            </button>
                        </button>

                        <!-- Veto Label Underneath (Same Width as Cup Button) -->
                        @if($vetoType || $isBo1FinalCup)
                            <span class="mt-2 px-2 py-1 rounded text-sm font-semibold text-center w-20 md:w-36
                                @if($vetoType === 'ban') bg-red-500 text-white 
                                @elseif($vetoType === 'pick') bg-green-500 text-white 
                                @elseif($vetoType === 'decider' || $isBo1FinalCup) bg-blue-500 text-white 
                                @endif">
                                @if($isDecider)
                                    Decider
                                @elseif($isBo1FinalCup)
                                    Cup to be Played
                                @else
                                    {{ ucfirst($vetoType) }}: {{ $vetoPlayer }}
                                @endif
                            </span>
                        @endif
                    </div>
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

<!-- Track List Modal -->
<div id="trackModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center">
    <div class="bg-gray-800 p-6 rounded-lg shadow-md w-96 text-center relative">
        <!-- Close Button -->
        <button onclick="closeTrackModal()" class="absolute top-2 right-2 text-white text-xl hover:text-gray-400">
            &times;
        </button>

        <!-- Modal Title with Cup Logo -->
        <h2 id="modalTitle" class="text-xl font-bold text-white mb-2 flex items-center justify-center">
            <img id="modalCupLogo" src="" alt="Cup Logo" class="w-6 h-6 mr-2"> 
            <span id="modalCupName"></span>
        </h2>

        <!-- Subtitle -->
        <p class="text-gray-400 mb-3">- All <span id="modalCupNameText"></span> Races -</p>

        <!-- Track List -->
        <ul id="trackList" class="text-white space-y-2"></ul>
    </div>
</div>

<!-- Race Details Modal -->
<div id="raceModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center">
    <div class="bg-gray-800 p-8 rounded-lg shadow-md w-[600px] text-center relative">
        <!-- Close Button -->
        <button onclick="closeRaceModal()" class="absolute top-3 right-3 text-white text-xl hover:text-gray-400">
            &times;
        </button>

        <!-- Race Title -->
        <h2 id="raceTitle" class="text-2xl font-bold text-white mb-4"></h2>

        <!-- Race Images -->
        <div class="flex justify-center items-center space-x-8">
            <!-- Main Track Image (Fixed Size) -->
            <div class="w-[320px] h-[180px] overflow-hidden flex items-center justify-center">
                <img id="mainRaceImage" src="" alt="Main Race Image" class="object-contain max-w-full max-h-full">
            </div>

            <!-- Divider -->
            <div class="w-px h-40 bg-gray-500"></div>

            <!-- Track Layout (Fixed Size) -->
            <div class="w-[220px] h-[180px] overflow-hidden flex items-center justify-center">
                <img id="layoutRaceImage" src="" alt="Track Layout" class="object-contain max-w-full max-h-full">
            </div>
        </div>
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

function showCupTracks(event, cupId) {
    event.preventDefault(); // Prevent accidental page refresh

    fetch(`/api/cups/${cupId}/tracks`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                return;
            }

            document.getElementById('modalCupLogo').src = data.cup_logo;
            document.getElementById('modalCupName').innerText = data.cup_name;
            document.getElementById('modalCupNameText').innerText = data.cup_name;

            // Update track list with clickable links
            let trackList = document.getElementById('trackList');
            trackList.innerHTML = "";
            data.tracks.forEach(track => {
                let listItem = document.createElement('li');
                let trackLink = document.createElement('a');
                trackLink.href = "#";
                trackLink.innerText = track.name;
                trackLink.classList.add('text-blue-400', 'hover:underline');
                trackLink.onclick = function (event) {
                    event.preventDefault();
                    showRaceDetails(track.name, track.track_image, track.track_layout);
                };

                listItem.appendChild(trackLink);
                trackList.appendChild(listItem);
            });

            document.getElementById('trackModal').classList.remove('hidden');
        })
        .catch(error => console.error("Error fetching tracks:", error));
}

function closeTrackModal() {
    document.getElementById('trackModal').classList.add('hidden');
}

function showRaceDetails(raceName, trackImage, trackLayout) {
    document.getElementById('raceTitle').innerText = raceName;
    document.getElementById('mainRaceImage').src = trackImage;
    document.getElementById('layoutRaceImage').src = trackLayout;

    document.getElementById('raceModal').classList.remove('hidden');
}

function closeRaceModal() {
    document.getElementById('raceModal').classList.add('hidden');
}
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
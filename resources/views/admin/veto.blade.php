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
                            </span>
                        @else
                            <span class="text-gray-400">{{ ucfirst($step) }}...</span>
                        @endif
                    </li>
                @endforeach

                <!-- Show "Cup to be Played" only when the 7 bans are done in BO1 -->
                @if ($matchSetup['format'] === 'bo1' && count($vetoHistory) === 7 && isset($remainingCup))
                    <li>
                        <span class="text-blue-400 font-bold">
                            🏆 Cup to be Played: {{ $remainingCup->name }}
                        </span>
                    </li>
                @endif
            </ul>
        </div>

        <!-- Cup Selection Form -->
        @if($currentStepMessage !== 'Veto process complete!')
            <form method="POST" action="{{ route('admin.matches.veto.process') }}">
                @csrf

                <div class="mb-4">
                    <label for="cup_id" class="block text-sm font-medium text-gray-300">Select a Cup</label>
                    <select name="cup_id" id="cup_id" required 
                            class="block w-full mt-1 p-2 bg-gray-700 border border-gray-600 rounded-md">
                        <option value="">-- Choose a Cup --</option>
                        @foreach ($availableCups as $cup)
                            <option value="{{ $cup->id }}" 
                                @if(in_array($cup->id, array_column($vetoHistory, 'cup_id'))) disabled @endif>
                                {{ $cup->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Confirm Veto Choice Button -->
                <button type="submit" 
                        class="w-full bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-3 px-6 rounded-lg transition">
                    ✅ Confirm Veto Choice
                </button>
            </form>
        @endif

        <!-- Start the Match Button (Only enabled when veto process is complete) -->
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
@endsection
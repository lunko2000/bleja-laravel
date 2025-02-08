<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('mario_kart_game_races', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained('mario_kart_games')->onDelete('cascade'); // Match reference
            $table->foreignId('cup_id')->constrained('mario_kart_game_cups')->onDelete('cascade'); // Cup reference
            $table->foreignId('track_id')->constrained('mario_kart_tracks')->onDelete('cascade'); // Track reference
            $table->integer('race_number'); // 1, 2, 3, or 4
            $table->json('placements'); // Stores positions as JSON (e.g., {"player1": 1, "player2": 3})
            $table->foreignId('winner')->nullable()->constrained('users')->onDelete('set null'); // Race winner
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mario_kart_game_races');
    }
};
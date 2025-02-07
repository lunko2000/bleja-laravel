<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('mario_kart_games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // Admin who created the match
            $table->foreignId('player1')->constrained('users')->onDelete('cascade'); // First player
            $table->foreignId('player2')->constrained('users')->onDelete('cascade'); // Second player
            $table->enum('status', ['pending', 'veto', 'in_progress', 'completed'])->default('pending'); // Match state
            $table->enum('format', ['bo1', 'bo3'])->default('bo1'); // Match format
            $table->foreignId('winner')->nullable()->constrained('users')->onDelete('set null'); // Stores winner after match ends
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mario_kart_games');
    }
};
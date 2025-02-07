<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('mario_kart_game_cups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained('mario_kart_games')->onDelete('cascade'); // Match reference
            $table->foreignId('cup_id')->constrained('mario_kart_cups')->onDelete('cascade'); // Cup selected
            $table->enum('type', ['pick', 'decider'])->default('pick'); // Picked or Decider cup
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mario_kart_game_cups');
    }
};
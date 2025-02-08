<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('mario_kart_game_cups', function (Blueprint $table) {
            $table->foreignId('cup_winner')->nullable()->constrained('users')->onDelete('set null'); // Store the winner of the cup
        });
    }

    public function down(): void
    {
        Schema::table('mario_kart_game_cups', function (Blueprint $table) {
            $table->dropForeign(['cup_winner']);
            $table->dropColumn('cup_winner');
        });
    }
};
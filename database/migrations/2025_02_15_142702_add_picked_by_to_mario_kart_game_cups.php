<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('mario_kart_game_cups', function (Blueprint $table) {
            $table->foreignId('picked_by')->nullable()->constrained('users')->onDelete('set null')->after('cup_id');
        });
    }

    public function down(): void
    {
        Schema::table('mario_kart_game_cups', function (Blueprint $table) {
            $table->dropForeign(['picked_by']);
            $table->dropColumn('picked_by');
        });
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('mario_kart_tracks', function (Blueprint $table) {
            $table->string('track_image')->nullable()->after('name');  // Column for track image
            $table->string('track_layout')->nullable()->after('track_image');  // Column for track layout
        });
    }

    public function down(): void
    {
        Schema::table('mario_kart_tracks', function (Blueprint $table) {
            $table->dropColumn(['track_image', 'track_layout']);
        });
    }
};
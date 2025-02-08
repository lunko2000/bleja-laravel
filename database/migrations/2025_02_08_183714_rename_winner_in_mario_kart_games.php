<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('mario_kart_games', function (Blueprint $table) {
            $table->renameColumn('winner', 'winner_id');
        });
    }

    public function down()
    {
        Schema::table('mario_kart_games', function (Blueprint $table) {
            $table->renameColumn('winner_id', 'winner');
        });
    }
};

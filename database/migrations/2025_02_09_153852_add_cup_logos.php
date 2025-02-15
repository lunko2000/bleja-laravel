<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('mario_kart_cups', function (Blueprint $table) {
            $table->string('cup_logo')->nullable()->after('name'); // Add cup_logo column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('mario_kart_cups', function (Blueprint $table) {
            $table->dropColumn('cup_logo'); // Allow rollback
        });
    }
    
};

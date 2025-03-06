<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Modify the existing role enum to include 'guest'
            $table->enum('role', ['admin', 'player', 'guest'])->default('player')->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Revert back to original enum values if needed
            $table->enum('role', ['admin', 'player'])->default('player')->change();
        });
    }
};
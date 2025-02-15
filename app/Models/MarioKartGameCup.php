<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarioKartGameCup extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'cup_id',
        'type', // 'ban', 'pick', or 'decider'
        'picked_by',
    ];

    // Relationship to MarioKartGame
    public function game()
    {
        return $this->belongsTo(MarioKartGame::class, 'game_id');
    }

    // Relationship to MarioKartCup
    public function cup()
    {
        return $this->belongsTo(MarioKartCup::class, 'cup_id');
    }

    public function races()
    {
        return $this->hasMany(MarioKartGameRace::class, 'cup_id');
    }

    // Relationship to the user who picked this cup
    public function picker()
    {
        return $this->belongsTo(User::class, 'picked_by');
    }
}
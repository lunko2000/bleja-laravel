<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarioKartGameRace extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'cup_id',
        'track_id',
        'race_number',
        'placements', // JSON of race results
        'winner', // Winner of the race
    ];

    protected $casts = [
        'placements' => 'json', // Ensure placements are automatically cast as an array
    ];

    // Relationship to the game
    public function game()
    {
        return $this->belongsTo(MarioKartGame::class, 'game_id');
    }

    // Relationship to the cup
    public function cup()
    {
        return $this->belongsTo(MarioKartGameCup::class, 'cup_id');
    }

    // Relationship to the track
    public function track()
    {
        return $this->belongsTo(MarioKartTrack::class, 'track_id');
    }

    // Relationship to the winner
    public function winner()
    {
        return $this->belongsTo(User::class, 'winner');
    }
}
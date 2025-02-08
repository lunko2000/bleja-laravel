<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarioKartGame extends Model
{
    use HasFactory;

    protected $fillable = [
        'player1',
        'player2',
        'created_by',
        'format',
        'status', // 'pending', 'vetoing', 'active', 'completed'
        'winner_id', // Nullable, will be filled when match is completed
    ];

    // ✅ Use meaningful relationship method names to avoid conflicts
    public function playerOne()
    {
        return $this->belongsTo(User::class, 'player1');
    }

    public function playerTwo()
    {
        return $this->belongsTo(User::class, 'player2');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function winner()
    {
        return $this->belongsTo(User::class, 'winner_id');
    }

    public function cups()
    {
        return $this->hasMany(MarioKartGameCup::class, 'game_id');
    }

    public function races()
    {
        return $this->hasMany(MarioKartGameRace::class, 'game_id');
    }
}
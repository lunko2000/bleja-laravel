<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarioKartCup extends Model
{
    protected $fillable = [
        'id',
        'name',
        'cup_logo',
    ];

    public function gameCups()
    {
        return $this->hasMany(MarioKartGameCup::class, 'cup_id');
    }
    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarioKartTrack extends Model
{
    protected $fillable = [
        'id',
        'name',
        'track_cup',
    ];
}

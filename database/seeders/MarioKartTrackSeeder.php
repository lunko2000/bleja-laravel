<?php

namespace Database\Seeders;

use App\Models\MarioKartTrack;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MarioKartTrackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Mario Kart Wii Race Tracks for the Database
        MarioKartTrack::where('id', '>', 0)->delete();

        MarioKartTrack::create([
            'id' => 1,
            'name' => 'Luigi Circuit',
            'track_cup' => 1, // Mushroom Cup
        ]);

        MarioKartTrack::create([
            'id' => 2,
            'name' => 'Moo Moo Meadows',
            'track_cup' => 1, // Mushroom Cup
        ]);

        MarioKartTrack::create([
            'id' => 3,
            'name' => 'Mushroom Gorge',
            'track_cup' => 1, // Mushroom Cup
        ]);

        MarioKartTrack::create([
            'id' => 4,
            'name' => 'Toad\'s Factory',
            'track_cup' => 1, // Mushroom Cup
        ]);

        MarioKartTrack::create([
            'id' => 5,
            'name' => 'Mario Circuit',
            'track_cup' => 2, // Flower Cup
        ]);

        MarioKartTrack::create([
            'id' => 6,
            'name' => 'Coconut Mall',
            'track_cup' => 2, // Flower Cup
        ]);

        MarioKartTrack::create([
            'id' => 7,
            'name' => 'DK Summit',
            'track_cup' => 2, // Flower Cup
        ]);

        MarioKartTrack::create([
            'id' => 8,
            'name' => 'Wario\'s Gold Mine',
            'track_cup' => 2, // Flower Cup
        ]);

        MarioKartTrack::create([
            'id' => 9,
            'name' => 'Daisy Circuit',
            'track_cup' => 3, // Star Cup
        ]);

        MarioKartTrack::create([
            'id' => 10,
            'name' => 'Koopa Cape',
            'track_cup' => 3, // Star Cup
        ]);

        MarioKartTrack::create([
            'id' => 11,
            'name' => 'Maple Treeway',
            'track_cup' => 3, // Star Cup
        ]);

        MarioKartTrack::create([
            'id' => 12,
            'name' => 'Grumble Volcano',
            'track_cup' => 3, // Star Cup
        ]);

        MarioKartTrack::create([
            'id' => 13,
            'name' => 'Dry Dry Ruins',
            'track_cup' => 4, // Special Cup
        ]);

        MarioKartTrack::create([
            'id' => 14,
            'name' => 'Moonview Highway',
            'track_cup' => 4, // Special Cup
        ]);

        MarioKartTrack::create([
            'id' => 15,
            'name' => 'Bowser\'s Castle',
            'track_cup' => 4, // Special Cup
        ]);

        MarioKartTrack::create([
            'id' => 16,
            'name' => 'Rainbow Road',
            'track_cup' => 4, // Special Cup
        ]);

        MarioKartTrack::create([
            'id' => 17,
            'name' => 'GCN Peach Beach',
            'track_cup' => 5, // Shell Cup
        ]);

        MarioKartTrack::create([
            'id' => 18,
            'name' => 'DS Yoshi Falls',
            'track_cup' => 5, // Shell Cup
        ]);

        MarioKartTrack::create([
            'id' => 19,
            'name' => 'SNES Ghost Valley 2',
            'track_cup' => 5, // Shell Cup
        ]);

        MarioKartTrack::create([
            'id' => 20,
            'name' => 'N64 Mario Raceway',
            'track_cup' => 5, // Shell Cup
        ]);

        MarioKartTrack::create([
            'id' => 21,
            'name' => 'N64 Sherbet Land',
            'track_cup' => 6, // Banana Cup
        ]);

        MarioKartTrack::create([
            'id' => 22,
            'name' => 'GBA Shy Guy Beach',
            'track_cup' => 6, // Banana Cup
        ]);

        MarioKartTrack::create([
            'id' => 23,
            'name' => 'DS Delfino Square',
            'track_cup' => 6, // Banana Cup
        ]);

        MarioKartTrack::create([
            'id' => 24,
            'name' => 'GCN Waluigi Stadium',
            'track_cup' => 6, // Banana Cup
        ]);

        MarioKartTrack::create([
            'id' => 25,
            'name' => 'DS Desert Hills',
            'track_cup' => 7, // Leaf Cup
        ]);

        MarioKartTrack::create([
            'id' => 26,
            'name' => 'GBA Bowser Castle 3',
            'track_cup' => 7, // Leaf Cup
        ]);

        MarioKartTrack::create([
            'id' => 27,
            'name' => 'N64 DK\'s Jungle Parkway',
            'track_cup' => 7, // Leaf Cup
        ]);

        MarioKartTrack::create([
            'id' => 28,
            'name' => 'GCN Mario Circuit',
            'track_cup' => 7, // Leaf Cup
        ]);

        MarioKartTrack::create([
            'id' => 29,
            'name' => 'SNES Mario Circuit 3',
            'track_cup' => 8, // Lightning Cup
        ]);

        MarioKartTrack::create([
            'id' => 30,
            'name' => 'DS Peach Gardens',
            'track_cup' => 8, // Lightning Cup
        ]);

        MarioKartTrack::create([
            'id' => 31,
            'name' => 'GCN DK Mountain',
            'track_cup' => 8, // Lightning Cup
        ]);

        MarioKartTrack::create([
            'id' => 32,
            'name' => 'N64 Bowser\'s Castle',
            'track_cup' => 8, // Lightning Cup
        ]);
    }
}

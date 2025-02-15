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
            'track_image' => 'luigi-circuit.jpg',
            'track_layout' => 'luigi-circuit-layout.jpg',
        ]);

        MarioKartTrack::create([
            'id' => 2,
            'name' => 'Moo Moo Meadows',
            'track_cup' => 1, // Mushroom Cup
            'track_image' => 'moo-moo-meadows.jpg',
            'track_layout' => 'moo-moo-meadows-layout.jpg',
        ]);

        MarioKartTrack::create([
            'id' => 3,
            'name' => 'Mushroom Gorge',
            'track_cup' => 1, // Mushroom Cup
            'track_image' => 'mushroom-gorge.jpg',
            'track_layout' => 'mushroom-gorge-layout.jpg',
        ]);

        MarioKartTrack::create([
            'id' => 4,
            'name' => 'Toad\'s Factory',
            'track_cup' => 1, // Mushroom Cup
            'track_image' => 'toads-factory.jpg',
            'track_layout' => 'toads-factory-layout.jpg',
        ]);

        MarioKartTrack::create([
            'id' => 5,
            'name' => 'Mario Circuit',
            'track_cup' => 2, // Flower Cup
            'track_image' => 'mario-circuit.jpg',
            'track_layout' => 'mario-circuit-layout.jpg',
        ]);

        MarioKartTrack::create([
            'id' => 6,
            'name' => 'Coconut Mall',
            'track_cup' => 2, // Flower Cup
            'track_image' => 'coconut-mall.jpg',
            'track_layout' => 'coconut-mall-layout.jpg',
        ]);

        MarioKartTrack::create([
            'id' => 7,
            'name' => 'DK Summit',
            'track_cup' => 2, // Flower Cup
            'track_image' => 'dk-summit.jpg',
            'track_layout' => 'dk-summit-layout.jpg',
        ]);

        MarioKartTrack::create([
            'id' => 8,
            'name' => 'Wario\'s Gold Mine',
            'track_cup' => 2, // Flower Cup
            'track_image' => 'warios-gold-mine.jpg',
            'track_layout' => 'warios-gold-mine-layout.jpg',
        ]);

        MarioKartTrack::create([
            'id' => 9,
            'name' => 'Daisy Circuit',
            'track_cup' => 3, // Star Cup
            'track_image' => 'daisy-circuit.jpg',
            'track_layout' => 'daisy-circuit-layout.jpg',
        ]);

        MarioKartTrack::create([
            'id' => 10,
            'name' => 'Koopa Cape',
            'track_cup' => 3, // Star Cup
            'track_image' => 'koopa-cape.jpg',
            'track_layout' => 'koopa-cape-layout.jpg',
        ]);

        MarioKartTrack::create([
            'id' => 11,
            'name' => 'Maple Treeway',
            'track_cup' => 3, // Star Cup
            'track_image' => 'maple-treeway.jpg',
            'track_layout' => 'maple-treeway-layout.jpg',
        ]);

        MarioKartTrack::create([
            'id' => 12,
            'name' => 'Grumble Volcano',
            'track_cup' => 3, // Star Cup
            'track_image' => 'grumble-volcano.jpg',
            'track_layout' => 'grumble-volcano-layout.jpg',
        ]);

        MarioKartTrack::create([
            'id' => 13,
            'name' => 'Dry Dry Ruins',
            'track_cup' => 4, // Special Cup
            'track_image' => 'dry-dry-ruins.jpg',
            'track_layout' => 'dry-dry-ruins-layout.jpg',
        ]);

        MarioKartTrack::create([
            'id' => 14,
            'name' => 'Moonview Highway',
            'track_cup' => 4, // Special Cup
            'track_image' => 'moonview-highway.jpg',
            'track_layout' => 'moonview-highway-layout.jpg',
        ]);

        MarioKartTrack::create([
            'id' => 15,
            'name' => 'Bowser\'s Castle',
            'track_cup' => 4, // Special Cup
            'track_image' => 'bowsers-castle.jpg',
            'track_layout' => 'bowsers-castle-layout.jpg',
        ]);

        MarioKartTrack::create([
            'id' => 16,
            'name' => 'Rainbow Road',
            'track_cup' => 4, // Special Cup
            'track_image' => 'rainbow-road.jpg',
            'track_layout' => 'rainbow-road-layout.jpg',
        ]);

        MarioKartTrack::create([
            'id' => 17,
            'name' => 'GCN Peach Beach',
            'track_cup' => 5, // Shell Cup
            'track_image' => 'gcn-peach-beach.jpg',
            'track_layout' => 'gcn-peach-beach-layout.jpg',
        ]);

        MarioKartTrack::create([
            'id' => 18,
            'name' => 'DS Yoshi Falls',
            'track_cup' => 5, // Shell Cup
            'track_image' => 'ds-yoshi-falls.jpg',
            'track_layout' => 'ds-yoshi-falls-layout.jpg',
        ]);

        MarioKartTrack::create([
            'id' => 19,
            'name' => 'SNES Ghost Valley 2',
            'track_cup' => 5, // Shell Cup
            'track_image' => 'snes-ghost-valley2.jpg',
            'track_layout' => 'snes-ghost-valley2-layout.jpg',
        ]);

        MarioKartTrack::create([
            'id' => 20,
            'name' => 'N64 Mario Raceway',
            'track_cup' => 5, // Shell Cup
            'track_image' => 'n64-mario-raceway.jpg',
            'track_layout' => 'n64-mario-raceway-layout.jpg',
        ]);

        MarioKartTrack::create([
            'id' => 21,
            'name' => 'N64 Sherbet Land',
            'track_cup' => 6, // Banana Cup
            'track_image' => 'n64-sherbert-land.jpg',
            'track_layout' => 'n64-sherbet-land-layout.jpg',
        ]);

        MarioKartTrack::create([
            'id' => 22,
            'name' => 'GBA Shy Guy Beach',
            'track_cup' => 6, // Banana Cup
            'track_image' => 'gba-shy-guy-beach.jpg',
            'track_layout' => 'gba-shy-guy-beach-layout.jpg',
        ]);

        MarioKartTrack::create([
            'id' => 23,
            'name' => 'DS Delfino Square',
            'track_cup' => 6, // Banana Cup
            'track_image' => 'ds-delfino-square.jpg',
            'track_layout' => 'ds-delfino-square-layout.jpg',
        ]);

        MarioKartTrack::create([
            'id' => 24,
            'name' => 'GCN Waluigi Stadium',
            'track_cup' => 6, // Banana Cup
            'track_image' => 'gcn-waluigi-stadium.jpg',
            'track_layout' => 'gcn-waluigi-stadium-layout.jpg',
        ]);

        MarioKartTrack::create([
            'id' => 25,
            'name' => 'DS Desert Hills',
            'track_cup' => 7, // Leaf Cup
            'track_image' => 'ds-desert-hills.jpg',
            'track_layout' => 'ds-desert-hills-layout.jpg',
        ]);

        MarioKartTrack::create([
            'id' => 26,
            'name' => 'GBA Bowser Castle 3',
            'track_cup' => 7, // Leaf Cup
            'track_image' => 'gba-bowser-castle3.jpg',
            'track_layout' => 'gba-bowser-castle3-layout.jpg',
        ]);

        MarioKartTrack::create([
            'id' => 27,
            'name' => 'N64 DK\'s Jungle Parkway',
            'track_cup' => 7, // Leaf Cup
            'track_image' => 'n64-dks-jungle-parkway.jpg',
            'track_layout' => 'n64-dks-jungle-parkway-layout.jpg',
        ]);

        MarioKartTrack::create([
            'id' => 28,
            'name' => 'GCN Mario Circuit',
            'track_cup' => 7, // Leaf Cup
            'track_image' => 'gcn-mario-circuit.jpg',
            'track_layout' => 'gcn-mario-circuit-layout.jpg',
        ]);

        MarioKartTrack::create([
            'id' => 29,
            'name' => 'SNES Mario Circuit 3',
            'track_cup' => 8, // Lightning Cup
            'track_image' => 'snes-mario-circuit3.jpg',
            'track_layout' => 'snes-mario-circuit3-layout.jpg',
        ]);

        MarioKartTrack::create([
            'id' => 30,
            'name' => 'DS Peach Gardens',
            'track_cup' => 8, // Lightning Cup
            'track_image' => 'ds-peach-gardens.jpg',
            'track_layout' => 'ds-peach-gardens-layout.jpg',
        ]);

        MarioKartTrack::create([
            'id' => 31,
            'name' => 'GCN DK Mountain',
            'track_cup' => 8, // Lightning Cup
            'track_image' => 'gcn-dk-mountain.jpg',
            'track_layout' => 'gcn-dk-mountain-layout.jpg',
        ]);

        MarioKartTrack::create([
            'id' => 32,
            'name' => 'N64 Bowser\'s Castle',
            'track_cup' => 8, // Lightning Cup
            'track_image' => 'n64-bowsers-castle.jpg',
            'track_layout' => 'n64-bowsers-castle-layout.jpg',
        ]);
    }
}

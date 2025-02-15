<?php

namespace Database\Seeders;

use App\Models\MarioKartCup;
use Illuminate\Database\Seeder;

class MarioKartCupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Delete existing cups before inserting new ones
        MarioKartCup::where('id', '>', 0)->delete();

        MarioKartCup::create([
            'id' => 1,
            'name' => 'Mushroom Cup',
            'cup_logo' => 'mushroom.png',
        ]);

        MarioKartCup::create([
            'id' => 2,
            'name' => 'Flower Cup',
            'cup_logo' => 'flower.png',
        ]);

        MarioKartCup::create([
            'id' => 3,
            'name' => 'Star Cup',
            'cup_logo' => 'star.png',
        ]);

        MarioKartCup::create([
            'id' => 4,
            'name' => 'Special Cup',
            'cup_logo' => 'special.png',
        ]);

        MarioKartCup::create([
            'id' => 5,
            'name' => 'Shell Cup',
            'cup_logo' => 'shell.png',
        ]);

        MarioKartCup::create([
            'id' => 6,
            'name' => 'Banana Cup',
            'cup_logo' => 'banana.png',
        ]);

        MarioKartCup::create([
            'id' => 7,
            'name' => 'Leaf Cup',
            'cup_logo' => 'leaf.png',
        ]);

        MarioKartCup::create([
            'id' => 8,
            'name' => 'Lightning Cup',
            'cup_logo' => 'lightning.png',
        ]);
    }
}

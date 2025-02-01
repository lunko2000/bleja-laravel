<?php

namespace Database\Seeders;

use App\Models\MarioKartCup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MarioKartCupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MarioKartCup::where('id', '>', 0)->delete();

        MarioKartCup::create([
            'id' => 1,
            'name' => 'Mushroom Cup',
        ]);

        MarioKartCup::create([
            'id' => 2,
            'name' => 'Flower Cup',
        ]);

        MarioKartCup::create([
            'id' => 3,
            'name' => 'Star Cup',
        ]);

        MarioKartCup::create([
            'id' => 4,
            'name' => 'Special Cup',
        ]);

        MarioKartCup::create([
            'id' => 5,
            'name' => 'Shell Cup',
        ]);

        MarioKartCup::create([
            'id' => 6,
            'name' => 'Banana Cup',
        ]);

        MarioKartCup::create([
            'id' => 7,
            'name' => 'Leaf Cup',
        ]);

        MarioKartCup::create([
            'id' => 8,
            'name' => 'Lightning Cup',
        ]);
    }
}

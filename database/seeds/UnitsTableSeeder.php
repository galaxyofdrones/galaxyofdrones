<?php

use Illuminate\Database\Seeder;
use Koodilab\Models\Unit;

class UnitsTableSeeder extends Seeder
{
    /**
     * {@inheritdoc}
     */
    public function run()
    {
        Unit::create([
            'name' => [
                'en' => 'Goliath',
            ],
            'type' => Unit::TYPE_TRANSPORTER,
            'is_unlocked' => true,
            'speed' => 2,
            'attack' => 1,
            'defense' => 1,
            'supply' => 10,
            'train_cost' => 340,
            'train_time' => 1800,
            'description' => [
                'en' => 'Transports the minerals.',
            ],
            'capacity' => 100,
        ]);

        Unit::create([
            'name' => [
                'en' => 'Icarus',
            ],
            'type' => Unit::TYPE_SCOUT,
            'is_unlocked' => true,
            'speed' => 5,
            'attack' => 1,
            'defense' => 1,
            'supply' => 8,
            'train_cost' => 170,
            'train_time' => 1500,
            'description' => [
                'en' => 'Scouts the stars or planets.',
            ],
            'detection' => 25,
        ]);

        Unit::create([
            'name' => [
                'en' => 'Phoenix',
            ],
            'type' => Unit::TYPE_FIGHTER,
            'is_unlocked' => true,
            'speed' => 4,
            'attack' => 25,
            'defense' => 10,
            'supply' => 1,
            'train_cost' => 170,
            'train_time' => 1320,
            'description' => [
                'en' => 'Fights in the battle.',
            ],
            'capacity' => 20,
        ]);

        Unit::create([
            'name' => [
                'en' => 'Falcon',
            ],
            'type' => Unit::TYPE_FIGHTER,
            'speed' => 3,
            'attack' => 150,
            'defense' => 50,
            'supply' => 5,
            'train_cost' => 470,
            'train_time' => 2400,
            'description' => [
                'en' => 'Fights in the battle.',
            ],
            'capacity' => 10,
            'research_experience' => 41125,
            'research_cost' => 82250,
            'research_time' => 164500,
        ]);

        Unit::create([
            'name' => [
                'en' => 'Viking',
            ],
            'type' => Unit::TYPE_FIGHTER,
            'speed' => 4,
            'attack' => 10,
            'defense' => 25,
            'supply' => 1,
            'train_cost' => 100,
            'train_time' => 840,
            'description' => [
                'en' => 'Fights in the battle.',
            ],
            'capacity' => 20,
            'research_experience' => 14875,
            'research_cost' => 29750,
            'research_time' => 58900,
        ]);

        Unit::create([
            'name' => [
                'en' => 'Raven',
            ],
            'type' => Unit::TYPE_FIGHTER,
            'speed' => 3,
            'attack' => 50,
            'defense' => 150,
            'supply' => 5,
            'train_cost' => 450,
            'train_time' => 2100,
            'description' => [
                'en' => 'Fights in the battle.',
            ],
            'capacity' => 10,
            'research_experience' => 39375,
            'research_cost' => 78750,
            'research_time' => 157500,
        ]);

        Unit::create([
            'name' => [
                'en' => 'Zeus',
            ],
            'type' => Unit::TYPE_HEAVY_FIGHTER,
            'speed' => 1,
            'attack' => 100,
            'defense' => 100,
            'supply' => 10,
            'train_cost' => 4800,
            'train_time' => 4800,
            'description' => [
                'en' => 'Destroys the buildings.',
            ],
            'research_experience' => 61250,
            'research_cost' => 122500,
            'research_time' => 245000,
        ]);

        Unit::create([
            'name' => [
                'en' => 'Helios',
            ],
            'type' => Unit::TYPE_SETTLER,
            'speed' => 1,
            'attack' => 1,
            'defense' => 1,
            'supply' => 100,
            'train_cost' => 31200,
            'train_time' => 10800,
            'description' => [
                'en' => 'Occupies the planets.',
            ],
            'research_experience' => 10282,
            'research_cost' => 20563,
            'research_time' => 41126,
        ]);
    }
}

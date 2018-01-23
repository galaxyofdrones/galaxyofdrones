<?php

use Illuminate\Database\Seeder;
use Koodilab\Models\Building;

class BuildingsTableSeeder extends Seeder
{
    /**
     * {@inheritdoc}
     */
    public function run()
    {
        Building::create([
            'name' => [
                'en' => 'Command Center',
            ],
            'type' => Building::TYPE_CENTRAL,
            'construction_experience' => 1432,
            'construction_cost' => 8185,
            'construction_time' => 143238,
            'end_level' => 10,
            'description' => [
                'en' => 'Forwards the commands to the buildings and drones.',
            ],
            'limit' => 1,
            'capacity' => 1000,
            'supply' => 1000,
            'production_rate' => 200,
            'construction_time_bonus' => 0.76,
        ]);

        Building::create([
            'parent_id' => 1,
            'name' => [
                'en' => 'Mine',
            ],
            'type' => Building::TYPE_MINER,
            'construction_experience' => 875,
            'construction_cost' => 5020,
            'construction_time' => 87850,
            'end_level' => 10,
            'description' => [
                'en' => 'Produces the minerals.',
            ],
            'mining_rate' => 600,
        ]);

        Building::create([
            'parent_id' => 1,
            'name' => [
                'en' => 'Power Plant',
            ],
            'type' => Building::TYPE_PRODUCER,
            'construction_experience' => 1125,
            'construction_cost' => 6433,
            'construction_time' => 112578,
            'end_level' => 10,
            'description' => [
                'en' => 'Produces the energy and transmutes the minerals.',
            ],
            'limit' => 1,
            'production_rate' => 400,
        ]);

        Building::create([
            'parent_id' => 3,
            'name' => [
                'en' => 'Drone Bay',
            ],
            'type' => Building::TYPE_CONTAINER,
            'construction_experience' => 1162,
            'construction_cost' => 6640,
            'construction_time' => 116200,
            'end_level' => 10,
            'description' => [
                'en' => 'Stores the drones.',
            ],
            'supply' => 20000,
        ]);

        Building::create([
            'parent_id' => 2,
            'name' => [
                'en' => 'Warehouse',
            ],
            'type' => Building::TYPE_CONTAINER,
            'construction_experience' => 922,
            'construction_cost' => 5270,
            'construction_time' => 92225,
            'end_level' => 10,
            'description' => [
                'en' => 'Stores the minerals.',
            ],
            'capacity' => 20000,
        ]);

        Building::create([
            'parent_id' => 3,
            'name' => [
                'en' => 'Sensor Tower',
            ],
            'type' => Building::TYPE_SCOUT,
            'construction_experience' => 2163,
            'construction_cost' => 12362,
            'construction_time' => 216335,
            'end_level' => 10,
            'description' => [
                'en' => 'Detects the incoming and outgoing movements.',
            ],
            'limit' => 1,
            'detection' => 600,
        ]);

        Building::create([
            'parent_id' => 5,
            'name' => [
                'en' => 'Trade Office',
            ],
            'type' => Building::TYPE_TRADER,
            'construction_experience' => 1473,
            'construction_cost' => 8421,
            'construction_time' => 147368,
            'end_level' => 10,
            'description' => [
                'en' => 'Trades between the planet and mothership.',
            ],
            'limit' => 1,
            'trade_time_bonus' => 0.65,
        ]);

        Building::create([
            'parent_id' => 4,
            'name' => [
                'en' => 'Drone Factory',
            ],
            'type' => Building::TYPE_TRAINER,
            'construction_experience' => 3077,
            'construction_cost' => 17587,
            'construction_time' => 307773,
            'end_level' => 10,
            'description' => [
                'en' => 'Produces the drones from the energy.',
            ],
            'limit' => 5,
            'train_time_bonus' => 0.84,
        ]);

        Building::create([
            'parent_id' => 6,
            'name' => [
                'en' => 'Missile Turret',
            ],
            'type' => Building::TYPE_DEFENSIVE,
            'construction_experience' => 1061,
            'construction_cost' => 6068,
            'construction_time' => 106190,
            'end_level' => 10,
            'description' => [
                'en' => 'Protects the colony from the incoming attacks.',
            ],
            'defense' => 200,
        ]);

        Building::create([
            'parent_id' => 9,
            'name' => [
                'en' => 'Shield Generator',
            ],
            'type' => Building::TYPE_DEFENSIVE,
            'construction_experience' => 3412,
            'construction_cost' => 19500,
            'construction_time' => 341250,
            'end_level' => 10,
            'description' => [
                'en' => 'Grants the defense bonus to the buildings and drones.',
            ],
            'limit' => 1,
            'defense_bonus' => 1.0,
        ]);
    }
}

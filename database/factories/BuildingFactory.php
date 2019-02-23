<?php

use Faker\Generator as Faker;

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Koodilab\Models\Building::class, function (Faker $faker) {
    return [
        'name' => [
            'en' => $faker->word,
        ],
        'description' => [
            'en' => $faker->word,
        ],
        'type' => $faker->numberBetween(0, Koodilab\Models\Building::TYPE_DEFENSIVE),
        'end_level' => 10,
        'construction_experience' => $faker->numberBetween(1, 1000),
        'construction_cost' => $faker->numberBetween(1, 1000),
        'construction_time' => $faker->numberBetween(1, 1000),
        'defense' => $faker->numberBetween(1, 100),
        'detection' => $faker->numberBetween(1, 100),
        'capacity' => $faker->numberBetween(1, 100),
        'supply' => $faker->numberBetween(1, 100),
        'mining_rate' => $faker->numberBetween(1, 100),
        'production_rate' => $faker->numberBetween(1, 100),
        'defense_bonus' => $faker->numberBetween(1, 100),
        'construction_time_bonus' => $faker->numberBetween(1, 100),
        'trade_time_bonus' => $faker->numberBetween(1, 100),
        'train_time_bonus' => $faker->numberBetween(1, 100),
    ];
});

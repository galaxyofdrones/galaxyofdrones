<?php

use Faker\Generator as Faker;

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Koodilab\Models\Unit::class, function (Faker $faker) {
    return [
        'name' => [
            'en' => $faker->word,
        ],
        'type' => $faker->numberBetween(0, Koodilab\Models\Unit::TYPE_SETTLER),
        'is_unlocked' => $faker->boolean,
        'speed' => $faker->numberBetween(1, 100),
        'attack' => $faker->numberBetween(1, 100),
        'defense' => $faker->numberBetween(1, 100),
        'supply' => $faker->numberBetween(1, 100),
        'train_cost' => $faker->numberBetween(1, 100),
        'train_time' => $faker->numberBetween(1, 100),
        'description' => [
            'en' => $faker->word,
        ],
        'sort_order' => $faker->numberBetween(1, 4),
    ];
});

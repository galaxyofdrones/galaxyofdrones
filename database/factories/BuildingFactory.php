<?php

use Faker\Generator as Faker;

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Koodilab\Models\Building::class, function (Faker $faker) {
    return [
        'type' => $faker->numberBetween(0, Koodilab\Models\Building::TYPE_DEFENSIVE),
        'end_level' => 10,
        'construction_experience' => $faker->numberBetween(1, 1000),
        'construction_cost' => $faker->numberBetween(1, 1000),
        'construction_time' => $faker->numberBetween(1, 1000),
    ];
});

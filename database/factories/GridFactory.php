<?php

use Faker\Generator as Faker;

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Koodilab\Models\Grid::class, function (Faker $faker) {
    return [
        'planet_id' => function () {
            return factory(Koodilab\Models\Planet::class)->create()->id;
        },
        'building_id' => function () {
            return factory(Koodilab\Models\Building::class)->create()->id;
        },
        'level' => $faker->numberBetween(1, 10),
        'x' => $faker->numberBetween(0, Koodilab\Starmap\Generator::GRID_COUNT),
        'y' => $faker->numberBetween(0, Koodilab\Starmap\Generator::GRID_COUNT),
        'type' => $faker->numberBetween(0, Koodilab\Models\Grid::TYPE_CENTRAL),
    ];
});

<?php

use Faker\Generator as Faker;

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Grid::class, function (Faker $faker) {
    return [
        'planet_id' => function () {
            return factory(App\Models\Planet::class)->create()->id;
        },
        'building_id' => function () {
            return factory(App\Models\Building::class)->create()->id;
        },
        'level' => $faker->numberBetween(1, 10),
        'x' => $faker->numberBetween(0, App\Starmap\Generator::GRID_COUNT),
        'y' => $faker->numberBetween(0, App\Starmap\Generator::GRID_COUNT),
        'type' => $faker->numberBetween(0, App\Models\Grid::TYPE_CENTRAL),
    ];
});

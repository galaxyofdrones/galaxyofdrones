<?php

use Faker\Generator as Faker;

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Koodilab\Models\Construction::class, function (Faker $faker) {
    return [
        'building_id' => function () {
            return factory(Koodilab\Models\Building::class)->create()->id;
        },
        'grid_id' => function () {
            return factory(Koodilab\Models\Grid::class)->create()->id;
        },
        'level' => $faker->numberBetween(1, 100),
        'ended_at' => $faker->dateTime(),
    ];
});

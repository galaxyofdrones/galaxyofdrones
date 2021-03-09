<?php

use Faker\Generator as Faker;

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Training::class, function (Faker $faker) {
    return [
        'grid_id' => function () {
            return factory(App\Models\Grid::class)->create()->id;
        },
        'unit_id' => function () {
            return factory(App\Models\Unit::class)->create()->id;
        },
        'quantity' => $faker->numberBetween(1, 50),
        'ended_at' => $faker->dateTime(),
    ];
});

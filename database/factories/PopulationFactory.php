<?php

use Faker\Generator as Faker;

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Koodilab\Models\Population::class, function (Faker $faker) {
    return [
        'planet_id' => function () {
            return factory(Koodilab\Models\Planet::class)->create()->id;
        },
        'unit_id' => function () {
            return factory(Koodilab\Models\Unit::class)->create()->id;
        },
        'quantity' => $faker->numberBetween(1, 1000),
    ];
});

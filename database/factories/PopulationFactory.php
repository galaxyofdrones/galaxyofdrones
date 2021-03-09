<?php

use Faker\Generator as Faker;

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Population::class, function (Faker $faker) {
    return [
        'planet_id' => function () {
            return factory(App\Models\Planet::class)->create()->id;
        },
        'unit_id' => function () {
            return factory(App\Models\Unit::class)->create()->id;
        },
        'quantity' => $faker->numberBetween(1, 1000),
    ];
});

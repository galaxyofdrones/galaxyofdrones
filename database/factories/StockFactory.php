<?php

use Faker\Generator as Faker;

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Koodilab\Models\Stock::class, function (Faker $faker) {
    return [
        'planet_id' => function () {
            return factory(Koodilab\Models\Planet::class)->create()->id;
        },
        'resource_id' => function () {
            return factory(Koodilab\Models\Resource::class)->create()->id;
        },
        'quantity' => $faker->numberBetween(1, 1000),
    ];
});

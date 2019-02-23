<?php

use Faker\Generator as Faker;

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Koodilab\Models\Shield::class, function (Faker $faker) {
    return [
        'planet_id' => function () {
            return factory(Koodilab\Models\Planet::class)->create()->id;
        },
        'ended_at' => $faker->dateTime(),
    ];
});

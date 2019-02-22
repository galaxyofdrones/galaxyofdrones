<?php

use Faker\Generator as Faker;

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Koodilab\Models\Upgrade::class, function (Faker $faker) {
    return [
        'level' => $faker->numberBetween(0, 100),
        'grid_id' => function () {
            return factory(Koodilab\Models\Star::class)->create()->id;
        },
        'ended_at' => $faker->dateTime(),
    ];
});

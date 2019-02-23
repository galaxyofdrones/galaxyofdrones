<?php

use Faker\Generator as Faker;

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Koodilab\Models\Expedition::class, function (Faker $faker) {
    return [
        'star_id' => function () {
            return factory(Koodilab\Models\Star::class)->create()->id;
        },
        'user_id' => function () {
            return factory(Koodilab\Models\User::class)->create()->id;
        },
        'experience' => $faker->numberBetween(1, 100),
        'solarion' => $faker->numberBetween(1, 50),
        'ended_at' => $faker->dateTime(),
    ];
});

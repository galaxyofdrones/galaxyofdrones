<?php

use Faker\Generator as Faker;

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Koodilab\Models\MissionLog::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(Koodilab\Models\User::class)->create()->id;
        },
        'energy' => $faker->numberBetween(1, 1000),
        'experience' => $faker->numberBetween(1, 100),
    ];
});

<?php

use Faker\Generator as Faker;

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Mission::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'energy' => $faker->numberBetween(0, 1000),
        'experience' => $faker->numberBetween(0, 100),
        'ended_at' => $faker->dateTime(),
    ];
});

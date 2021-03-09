<?php

use Faker\Generator as Faker;

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\ExpeditionLog::class, function (Faker $faker) {
    return [
        'star_id' => function () {
            return factory(App\Models\Star::class)->create()->id;
        },
        'user_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'experience' => $faker->numberBetween(1, 100),
        'solarion' => $faker->numberBetween(1, 50),
    ];
});

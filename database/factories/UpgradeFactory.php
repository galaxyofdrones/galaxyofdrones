<?php

use Faker\Generator as Faker;

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Upgrade::class, function (Faker $faker) {
    return [
        'level' => $faker->numberBetween(0, 100),
        'grid_id' => function () {
            return factory(App\Models\Star::class)->create()->id;
        },
        'ended_at' => $faker->dateTime(),
    ];
});

<?php

use Faker\Generator as Faker;

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Movement::class, function (Faker $faker) {
    return [
        'start_id' => function () {
            return factory(App\Models\Planet::class)->create()->id;
        },
        'end_id' => function () {
            return factory(App\Models\Planet::class)->create()->id;
        },
        'user_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'type' => $faker->numberBetween(0, App\Models\Movement::TYPE_PATROL),
        'ended_at' => $faker->dateTime(),
    ];
});

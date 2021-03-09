<?php

use Faker\Generator as Faker;

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Rank::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'mission_count' => $faker->numberBetween(1, 50),
        'expedition_count' => $faker->numberBetween(1, 50),
        'planet_count' => $faker->numberBetween(1, 50),
        'winning_battle_count' => $faker->numberBetween(1, 50),
        'losing_battle_count' => $faker->numberBetween(1, 50),
    ];
});

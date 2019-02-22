<?php

use Faker\Generator as Faker;

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Koodilab\Models\Research::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(Koodilab\Models\User::class)->create()->id;
        },
        'ended_at' => $faker->dateTimeBetween('+1 week', '+1 month'),
    ];
});

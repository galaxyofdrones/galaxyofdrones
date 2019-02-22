<?php

use Faker\Generator as Faker;

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Koodilab\Models\Bookmark::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'star_id' => function () {
            return factory(Koodilab\Models\Star::class)->create()->id;
        },
        'user_id' => function () {
            return factory(Koodilab\Models\User::class)->create()->id;
        },
    ];
});

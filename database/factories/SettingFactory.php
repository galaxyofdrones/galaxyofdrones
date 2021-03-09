<?php

use Faker\Generator as Faker;

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Setting::class, function (Faker $faker) {
    return [
        'key' => $faker->unique()->word,
        'value' => [
            'en' => $faker->word,
        ],
    ];
});

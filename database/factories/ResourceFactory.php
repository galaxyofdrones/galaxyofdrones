<?php

use Faker\Generator as Faker;

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Koodilab\Models\Resource::class, function (Faker $faker) {
    return [
        'name' => [
            'en' => $faker->word,
        ],
        'is_unlocked' => $faker->boolean,
        'frequency' => $faker->randomFloat(2, 0, 1),
        'efficiency' => $faker->randomFloat(2, 0, 1),
        'description' => [
            'en' => $faker->text,
        ],
    ];
});

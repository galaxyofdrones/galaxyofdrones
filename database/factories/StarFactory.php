<?php

use Faker\Generator as Faker;

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Koodilab\Models\Star::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'x' => $faker->numberBetween(0, Koodilab\Starmap\Generator::SIZE),
        'y' => $faker->numberBetween(0, Koodilab\Starmap\Generator::SIZE),
    ];
});

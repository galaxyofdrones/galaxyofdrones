<?php

use Faker\Generator as Faker;

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Koodilab\Models\Planet::class, function (Faker $faker) {
    return [
        'resource_id' => function () {
            return factory(Koodilab\Models\Resource::class)->create()->id;
        },
        'user_id' => function () {
            return factory(Koodilab\Models\User::class)->create()->id;
        },
        'name' => $faker->word,
        'x' => $faker->numberBetween(0, Koodilab\Starmap\Generator::SIZE),
        'y' => $faker->numberBetween(0, Koodilab\Starmap\Generator::SIZE),
        'size' => $faker->numberBetween(Koodilab\Models\Planet::SIZE_SMALL, Koodilab\Models\Planet::SIZE_LARGE),
    ];
});

<?php

use Faker\Generator as Faker;

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Planet::class, function (Faker $faker) {
    return [
        'resource_id' => function () {
            return factory(App\Models\Resource::class)->create()->id;
        },
        'user_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'name' => $faker->word,
        'x' => $faker->numberBetween(0, App\Starmap\Generator::SIZE),
        'y' => $faker->numberBetween(0, App\Starmap\Generator::SIZE),
        'size' => $faker->numberBetween(App\Models\Planet::SIZE_SMALL, App\Models\Planet::SIZE_LARGE),
    ];
});

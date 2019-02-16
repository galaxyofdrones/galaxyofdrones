<?php

use Faker\Generator as Faker;

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Koodilab\Models\Research::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(Koodilab\Models\User::class)->create()->id;
        },
        'researchable_type' => \Koodilab\Models\Unit::class,
        'researchable_id' => factory(Koodilab\Models\Unit::class)->create()->id,
        'ended_at' => $faker->dateTime(),
    ];
});

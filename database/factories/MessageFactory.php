<?php

use Faker\Generator as Faker;

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Koodilab\Models\Message::class, function (Faker $faker) {
    return [
        'sender_id' => function () {
            return factory(Koodilab\Models\User::class)->create()->id;
        },
        'recipient_id' => function () {
            return factory(Koodilab\Models\User::class)->create()->id;
        },
        'message' => $faker->text,
    ];
});

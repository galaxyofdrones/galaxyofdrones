<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Koodilab\Models\Block::class, function () {
    return [
        'blocked_id' => function () {
            return factory(Koodilab\Models\User::class)->create()->id;
        },
        'user_id' => function () {
            return factory(Koodilab\Models\User::class)->create()->id;
        },
    ];
});

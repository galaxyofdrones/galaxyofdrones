<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Block::class, function () {
    return [
        'blocked_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'user_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
    ];
});

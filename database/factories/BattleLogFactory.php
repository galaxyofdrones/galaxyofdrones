<?php

use Faker\Generator as Faker;

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Koodilab\Models\BattleLog::class, function (Faker $faker) {
    return [
        'start_id' => function () {
            return factory(Koodilab\Models\Planet::class)->create()->id;
        },
        'end_id' => function () {
            return factory(Koodilab\Models\Planet::class)->create()->id;
        },
        'attacker_id' => function () {
            return factory(Koodilab\Models\User::class)->create()->id;
        },
        'defender_id' => function () {
            return factory(Koodilab\Models\User::class)->create()->id;
        },
        'start_name' => function (array $battleLog) {
            return Koodilab\Models\Planet::find($battleLog['start_id'])->name;
        },
        'end_name' => function (array $battleLog) {
            return Koodilab\Models\Planet::find($battleLog['end_id'])->name;
        },
        'type' => $faker->numberBetween(0, Koodilab\Models\BattleLog::TYPE_OCCUPY),
        'winner' => $faker->numberBetween(0, Koodilab\Models\BattleLog::WINNER_DEFENDER),
    ];
});

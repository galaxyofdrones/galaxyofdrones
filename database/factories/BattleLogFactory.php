<?php

namespace Database\Factories;

use App\Models\BattleLog;
use App\Models\Planet;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BattleLogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BattleLog::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'start_id' => function () {
                return Planet::factory()->create()->id;
            },
            'end_id' => function () {
                return Planet::factory()->create()->id;
            },
            'attacker_id' => function () {
                return User::factory()->create()->id;
            },
            'defender_id' => function () {
                return User::factory()->create()->id;
            },
            'start_name' => function (array $battleLog) {
                return Planet::find($battleLog['start_id'])->name;
            },
            'end_name' => function (array $battleLog) {
                return Planet::find($battleLog['end_id'])->name;
            },
            'type' => $this->faker->numberBetween(0, BattleLog::TYPE_OCCUPY),
            'winner' => $this->faker->numberBetween(0, BattleLog::WINNER_DEFENDER),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Movement;
use App\Models\Planet;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MovementFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Movement::class;

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
            'user_id' => function () {
                return User::factory()->create()->id;
            },
            'type' => $this->faker->numberBetween(0, Movement::TYPE_PATROL),
            'ended_at' => $this->faker->dateTime(),
        ];
    }
}

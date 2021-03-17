<?php

namespace Database\Factories;

use App\Models\MissionLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MissionLogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MissionLog::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => function () {
                return User::factory()->create()->id;
            },
            'energy' => $this->faker->numberBetween(1, 1000),
            'experience' => $this->faker->numberBetween(1, 100),
        ];
    }
}

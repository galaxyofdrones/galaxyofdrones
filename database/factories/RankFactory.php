<?php

namespace Database\Factories;

use App\Models\Rank;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RankFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Rank::class;

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
            'mission_count' => $this->faker->numberBetween(1, 50),
            'expedition_count' => $this->faker->numberBetween(1, 50),
            'planet_count' => $this->faker->numberBetween(1, 50),
            'winning_battle_count' => $this->faker->numberBetween(1, 50),
            'losing_battle_count' => $this->faker->numberBetween(1, 50),
        ];
    }
}

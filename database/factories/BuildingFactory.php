<?php

namespace Database\Factories;

use App\Models\Building;
use Illuminate\Database\Eloquent\Factories\Factory;

class BuildingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Building::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => [
                'en' => $this->faker->word,
            ],
            'description' => [
                'en' => $this->faker->word,
            ],
            'type' => $this->faker->numberBetween(0, Building::TYPE_DEFENSIVE),
            'end_level' => 10,
            'construction_experience' => $this->faker->numberBetween(1, 1000),
            'construction_cost' => $this->faker->numberBetween(1, 1000),
            'construction_time' => $this->faker->numberBetween(1, 1000),
            'defense' => $this->faker->numberBetween(1, 100),
            'detection' => $this->faker->numberBetween(1, 100),
            'capacity' => $this->faker->numberBetween(1, 100),
            'supply' => $this->faker->numberBetween(1, 100),
            'mining_rate' => $this->faker->numberBetween(1, 100),
            'production_rate' => $this->faker->numberBetween(1, 100),
            'defense_bonus' => $this->faker->numberBetween(1, 100),
            'construction_time_bonus' => $this->faker->numberBetween(1, 100),
            'trade_time_bonus' => $this->faker->numberBetween(1, 100),
            'train_time_bonus' => $this->faker->numberBetween(1, 100),
        ];
    }
}

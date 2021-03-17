<?php

namespace Database\Factories;

use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

class UnitFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Unit::class;

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
            'type' => $this->faker->numberBetween(0, Unit::TYPE_SETTLER),
            'is_unlocked' => $this->faker->boolean,
            'speed' => $this->faker->numberBetween(1, 100),
            'attack' => $this->faker->numberBetween(1, 100),
            'defense' => $this->faker->numberBetween(1, 100),
            'supply' => $this->faker->numberBetween(1, 100),
            'train_cost' => $this->faker->numberBetween(1, 100),
            'train_time' => $this->faker->numberBetween(1, 100),
            'description' => [
                'en' => $this->faker->word,
            ],
            'sort_order' => $this->faker->numberBetween(1, 4),
        ];
    }
}

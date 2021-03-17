<?php

namespace Database\Factories;

use App\Models\Star;
use App\Starmap\Generator;
use Illuminate\Database\Eloquent\Factories\Factory;

class StarFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Star::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'x' => $this->faker->numberBetween(0, Generator::SIZE),
            'y' => $this->faker->numberBetween(0, Generator::SIZE),
        ];
    }
}

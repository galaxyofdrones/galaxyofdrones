<?php

namespace Database\Factories;

use App\Models\Planet;
use App\Models\Population;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

class PopulationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Population::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'planet_id' => function () {
                return Planet::factory()->create()->id;
            },
            'unit_id' => function () {
                return Unit::factory()->create()->id;
            },
            'quantity' => $this->faker->numberBetween(1, 1000),
        ];
    }
}

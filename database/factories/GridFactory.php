<?php

namespace Database\Factories;

use App\Models\Building;
use App\Models\Grid;
use App\Models\Planet;
use App\Starmap\Generator;
use Illuminate\Database\Eloquent\Factories\Factory;

class GridFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Grid::class;

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
            'building_id' => function () {
                return Building::factory()->create()->id;
            },
            'level' => $this->faker->numberBetween(1, 10),
            'x' => $this->faker->numberBetween(0, Generator::GRID_COUNT),
            'y' => $this->faker->numberBetween(0, Generator::GRID_COUNT),
            'type' => $this->faker->numberBetween(0, Grid::TYPE_CENTRAL),
        ];
    }
}

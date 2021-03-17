<?php

namespace Database\Factories;

use App\Models\Building;
use App\Models\Construction;
use App\Models\Grid;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConstructionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Construction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'building_id' => function () {
                return Building::factory()->create()->id;
            },
            'grid_id' => function () {
                return Grid::factory()->create()->id;
            },
            'level' => $this->faker->numberBetween(1, 100),
            'ended_at' => $this->faker->dateTime(),
        ];
    }
}

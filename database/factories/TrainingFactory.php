<?php

namespace Database\Factories;

use App\Models\Grid;
use App\Models\Training;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrainingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Training::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'grid_id' => function () {
                return Grid::factory()->create()->id;
            },
            'unit_id' => function () {
                return Unit::factory()->create()->id;
            },
            'quantity' => $this->faker->numberBetween(1, 50),
            'ended_at' => $this->faker->dateTime(),
        ];
    }
}

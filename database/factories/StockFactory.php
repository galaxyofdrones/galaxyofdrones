<?php

namespace Database\Factories;

use App\Models\Planet;
use App\Models\Resource;
use App\Models\Stock;
use Illuminate\Database\Eloquent\Factories\Factory;

class StockFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Stock::class;

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
            'resource_id' => function () {
                return Resource::factory()->create()->id;
            },
            'quantity' => $this->faker->numberBetween(1, 1000),
        ];
    }
}

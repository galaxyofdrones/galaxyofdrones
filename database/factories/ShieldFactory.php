<?php

namespace Database\Factories;

use App\Models\Planet;
use App\Models\Shield;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShieldFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Shield::class;

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
            'ended_at' => $this->faker->dateTime(),
        ];
    }
}

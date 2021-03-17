<?php

namespace Database\Factories;

use App\Models\Planet;
use App\Models\Resource;
use App\Models\User;
use App\Starmap\Generator;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlanetFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Planet::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'resource_id' => function () {
                return Resource::factory()->create()->id;
            },
            'user_id' => function () {
                return User::factory()->create()->id;
            },
            'name' => $this->faker->word,
            'x' => $this->faker->numberBetween(0, Generator::SIZE),
            'y' => $this->faker->numberBetween(0, Generator::SIZE),
            'size' => $this->faker->numberBetween(Planet::SIZE_SMALL, Planet::SIZE_LARGE),
        ];
    }
}

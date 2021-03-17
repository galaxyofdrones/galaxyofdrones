<?php

namespace Database\Factories;

use App\Models\Resource;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResourceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Resource::class;

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
            'is_unlocked' => $this->faker->boolean,
            'frequency' => $this->faker->randomFloat(2, 0, 1),
            'efficiency' => $this->faker->randomFloat(2, 0, 1),
            'description' => [
                'en' => $this->faker->text,
            ],
        ];
    }
}

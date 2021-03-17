<?php

namespace Database\Factories;

use App\Models\Star;
use App\Models\Upgrade;
use Illuminate\Database\Eloquent\Factories\Factory;

class UpgradeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Upgrade::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'level' => $this->faker->numberBetween(0, 100),
            'grid_id' => function () {
                return Star::factory()->create()->id;
            },
            'ended_at' => $this->faker->dateTime(),
        ];
    }
}

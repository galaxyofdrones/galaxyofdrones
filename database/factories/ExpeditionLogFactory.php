<?php

namespace Database\Factories;

use App\Models\ExpeditionLog;
use App\Models\Star;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpeditionLogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ExpeditionLog::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'star_id' => function () {
                return Star::factory()->create()->id;
            },
            'user_id' => function () {
                return User::factory()->create()->id;
            },
            'experience' => $this->faker->numberBetween(1, 100),
            'solarion' => $this->faker->numberBetween(1, 50),
        ];
    }
}

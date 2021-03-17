<?php

namespace Database\Factories;

use App\Models\Bookmark;
use App\Models\Star;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookmarkFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Bookmark::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'star_id' => function () {
                return Star::factory()->create()->id;
            },
            'user_id' => function () {
                return Star::factory()->create()->id;
            },
        ];
    }
}

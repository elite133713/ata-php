<?php

namespace Database\Factories;

use App\Models\Theater;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Theater>
 */
class TheaterFactory extends Factory
{
    protected $model = Theater::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company . ' Cinema',
            'location' => $this->faker->city,
            'rating' => mt_rand(1,5) / mt_getrandmax()
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Movie;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Movie>
 */
class MovieFactory extends Factory
{
    protected $model = Movie::class;

    public function definition()
    {
        return [
            'title' => $this->faker->unique()->sentence(3),
            'genre' => $this->faker->randomElement(['Action', 'Comedy', 'Drama', 'Horror', 'Romance', 'Sci-Fi']),
            'release_date' => $this->faker->dateTimeBetween('-2 years', '+1 year')->format('Y-m-d'),
            'description' => $this->faker->text(1000)
        ];
    }
}

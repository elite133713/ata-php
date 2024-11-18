<?php

namespace Database\Factories;

use App\Models\Movie;
use App\Models\Sale;
use App\Models\Theater;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Sale>
 */
class SaleFactory extends Factory
{
    protected $model = Sale::class;

    public function definition()
    {
        return [
            'theater_id' => Theater::factory(),
            'movie_id' => Movie::factory(),
            'sale_date' => $this->faker->date(),
            'tickets_sold' => $this->faker->numberBetween(50, 200),
        ];
    }
}

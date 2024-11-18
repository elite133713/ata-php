<?php

namespace Database\Seeders;

use App\Models\Theater;
use Illuminate\Database\Seeder;

class TheaterSeeder extends Seeder
{
    public function run()
    {
        Theater::factory()->count(1000)->create();
    }
}

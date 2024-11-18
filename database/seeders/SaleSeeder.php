<?php

namespace Database\Seeders;

use App\Models\Movie;
use App\Models\Sale;
use App\Models\Theater;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SaleSeeder extends Seeder
{
    public function run()
    {
        Model::unguard();
        Model::unsetEventDispatcher();
        $this->command->info('Starting SaleSeeder...');

        // Disable query logs for performance
        DB::connection()->disableQueryLog();

        $theaterIds = Theater::pluck('id')->toArray();
        $movieIds = Movie::pluck('id')->toArray();

        $startDate = Carbon::create(2024, 1, 1);
        $endDate = Carbon::create(2024, 12, 1);
        $datePeriod = new \DatePeriod(
            $startDate,
            new \DateInterval('P1D'),
            $endDate->addDay()
        );

        $totalDays = iterator_count($datePeriod);

        $totalSales = 1000000;
        $salesPerDay = intdiv($totalSales, $totalDays);

        $this->command->info("Generating $salesPerDay sales per day over $totalDays days.");

        $chunkSize = 5000;

        foreach ($datePeriod as $date) {
            $this->command->info('Processing date: ' . $date->format('Y-m-d'));

            $salesData = [];
            $usedCombinations = [];

            while (count($salesData) < $salesPerDay) {
                $theaterId = $theaterIds[array_rand($theaterIds)];
                $movieId = $movieIds[array_rand($movieIds)];

                $combinationKey = $theaterId . '-' . $movieId;

                if (!isset($usedCombinations[$combinationKey])) {
                    $usedCombinations[$combinationKey] = true;

                    $salesData[] = [
                        'theater_id' => $theaterId,
                        'movie_id' => $movieId,
                        'sale_date' => $date->format('Y-m-d'),
                        'tickets_sold' => rand(0, 1000),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    if (count($salesData) >= $chunkSize) {
                        Sale::insert($salesData);
                        $salesData = [];
                    }
                }
            }

            if (count($salesData) > 0) {
                Sale::insert($salesData);
            }
        }

        $this->command->info('SaleSeeder completed.');
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class HighestSalesTheater extends Command
{
    protected $signature = 'sales:highest-theater';
    protected $description = 'Find the theater with the highest sales on a given date';

    public function handle()
    {
        $dateInput = $this->ask('Enter a date (YYYY-MM-DD)');

        // Validate date format
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateInput)) {
            $this->error('Invalid date format. Please use YYYY-MM-DD.');
            return;
        }

        // Prevent SQL Injection by using parameter binding
        $results = DB::table('sales')
            ->select('theaters.name', DB::raw('SUM(tickets_sold) as total_sales'))
            ->join('theaters', 'sales.theater_id', '=', 'theaters.id')
            ->where('sale_date', $dateInput)
            ->groupBy('theaters.name')
            ->orderByDesc('total_sales')
            ->first();

        if ($results) {
            $this->info("Theater with highest sales on {$dateInput}:");
            $this->info("Theater: {$results->name}, Tickets Sold: {$results->total_sales}");
        } else {
            $this->info("No sales data found for {$dateInput}.");
        }
    }
}

<?php

namespace App\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SaleRepositories
{
    public function fetchHighestSalesForDate(string $date): ?\stdClass
    {
        return DB::table('sales')
            ->select('theaters.id', 'theaters.name', DB::raw('SUM(tickets_sold) as total_sales'))
            ->join('theaters', 'sales.theater_id', '=', 'theaters.id')
            ->where('sale_date', $date)
            ->groupBy('theaters.id', 'theaters.name')
            ->orderByDesc('total_sales')
            ->first();
    }

    public function fetchMonthlySales(int $theaterId, string $startOfMonth, string $endOfMonth): Collection
    {
        return DB::table('sales')
            ->select('sale_date', DB::raw('SUM(tickets_sold) as total_sales'))
            ->whereBetween('sale_date', [$startOfMonth, $endOfMonth])
            ->where('theater_id', $theaterId)
            ->groupBy('sale_date')
            ->orderBy('sale_date')
            ->get();
    }
}

<?php

namespace App\Http\Controllers;

use App\Repositories\SaleRepositories;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function index()
    {
        return view('highest-sales', ['sale_date' => null, 'errors' => []]);
    }

    public function show(Request $request, SaleRepositories $repositories)
    {
        $request->validate([
            'sale_date' => 'required|date',
        ]);

        $dateInput = $request->input('sale_date');
        $selectedDate = Carbon::createFromFormat('Y-m-d', $dateInput);

        $results = $repositories->fetchHighestSalesForDate($dateInput);

        $startOfMonth = $selectedDate->copy()->startOfMonth()->format('Y-m-d');
        $endOfMonth = $selectedDate->copy()->endOfMonth()->format('Y-m-d');

        $dates = [];
        $sales = [];

        if ($results) {
            $monthlySales = $repositories->fetchMonthlySales($results->id, $startOfMonth, $endOfMonth);

            foreach ($monthlySales as $sale) {
                $dates[] = Carbon::parse($sale->sale_date)->toDateString();
                $sales[] = $sale->total_sales;
            }

            return view('highest-sales', [
                'theater' => $results->name,
                'tickets_sold' => $results->total_sales,
                'sale_date' => $dateInput,
                'dates' => $dates,
                'sales' => $sales,
                'errors' => [],
            ]);
        }

        return view('highest-sales', [
            'message' => 'No sales data found for ' . $dateInput,
            'sale_date' => $dateInput,
            'dates' => $dates,
            'sales' => $sales,
            'errors' => [],
        ]);
    }
}

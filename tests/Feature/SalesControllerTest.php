<?php

namespace Tests\Feature;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Theater;
use App\Models\Movie;
use App\Models\Sale;

class SalesControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    /** @test */
    public function index_page_loads_successfully()
    {
        $response = $this->get(route('highest-sales'));

        $response->assertStatus(200);
        $response->assertViewIs('highest-sales');
        $response->assertViewHas('sale_date', null);
    }

    /** @test */
    public function show_page_displays_correct_data_for_valid_date()
    {
        $theater = Theater::factory()->create(['name' => 'Sample Theater']);
        $movie = Movie::factory()->create();
        $saleDate = '2024-05-15';

        Sale::factory()->create([
            'theater_id' => $theater->id,
            'movie_id' => $movie->id,
            'sale_date' => $saleDate,
            'tickets_sold' => 100,
        ]);


        $response = $this->post(route('highest-sales.show'), [
                'sale_date' => $saleDate,
            ]);

        $response->assertStatus(200);
        $response->assertViewIs('highest-sales');
        $response->assertViewHas('theater', 'Sample Theater');
        $response->assertViewHas('tickets_sold', 100);
        $response->assertViewHas('sale_date', $saleDate);
        $response->assertViewHas('dates');
        $response->assertViewHas('sales');
    }

    /** @test */
    public function show_page_displays_validation_error_for_invalid_date()
    {
        $response = $this->post(route('highest-sales.show'), [
            'sale_date' => 'invalid-date',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['sale_date']);
    }

    /** @test */
    public function show_page_displays_message_when_no_sales_data_found()
    {
        $saleDate = '2024-05-15';

        $response = $this->post(route('highest-sales.show'), [
            'sale_date' => $saleDate,
        ]);

        $response->assertStatus(200);
        $response->assertViewIs('highest-sales');
        $response->assertViewHas('message', 'No sales data found for ' . $saleDate);
        $response->assertViewHas('sale_date', $saleDate);
    }

    /** @test */
    public function show_page_returns_highest_sales_among_multiple_theaters()
    {
        $theater1 = Theater::factory()->create(['name' => 'Theater One']);
        $theater2 = Theater::factory()->create(['name' => 'Theater Two']);
        $movie = Movie::factory()->create();
        $saleDate = '2024-05-15';

        Sale::factory()->create([
            'theater_id' => $theater1->id,
            'movie_id' => $movie->id,
            'sale_date' => $saleDate,
            'tickets_sold' => 150,
        ]);

        Sale::factory()->create([
            'theater_id' => $theater2->id,
            'movie_id' => $movie->id,
            'sale_date' => $saleDate,
            'tickets_sold' => 200,
        ]);

        $response = $this->post(route('highest-sales.show'), [
            'sale_date' => $saleDate,
        ]);

        $response->assertStatus(200);
        $response->assertViewIs('highest-sales');
        $response->assertViewHas('theater', 'Theater Two');
        $response->assertViewHas('tickets_sold', 200);
        $response->assertViewHas('sale_date', $saleDate);
    }
}

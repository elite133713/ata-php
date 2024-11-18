<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('theater_id')->index()->constrained('theaters');
            $table->foreignId('movie_id')->index()->constrained('movies');
            $table->date('sale_date')->index();
            $table->integer('tickets_sold');
            $table->timestamps();

            $table->unique(['theater_id', 'movie_id', 'sale_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};

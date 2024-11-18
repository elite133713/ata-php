<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SalesController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/highest-sales', [SalesController::class, 'index'])->name('highest-sales');
Route::post('/highest-sales', [SalesController::class, 'show'])->name('highest-sales.show');

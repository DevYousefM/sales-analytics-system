<?php

use App\Http\Controllers\SalesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/products', [SalesController::class, 'products'])->name('products');
Route::get('/orders', [SalesController::class, 'orders'])->name('orders');
Route::get('/dashboard', [SalesController::class, 'dashboard'])->name('dashboard');

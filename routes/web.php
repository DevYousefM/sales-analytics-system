<?php

use App\Events\ProductEvent;
use App\Http\Controllers\SalesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});
Route::get('/products', [SalesController::class, 'products'])->name('products');
Route::prefix('/orders')->group(function () {
    Route::get('/', [SalesController::class, 'orders'])->name('orders');
    Route::get('/create', [SalesController::class, 'addOrder'])->name('orders.create');
});

Route::get('/dashboard', [SalesController::class, 'dashboard'])->name('dashboard');

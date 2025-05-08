<?php

use App\Http\Controllers\Api\SalesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::prefix('sales')->group(function () {});
Route::prefix('products')->group(function () {
    Route::post('/create', [SalesController::class, 'addProduct']);
    Route::get('/get', [SalesController::class, 'products']);
});

Route::prefix('orders')->group(function () {
    Route::post('/create', [SalesController::class, 'addOrder']);
});

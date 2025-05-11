<?php

use App\Http\Controllers\Api\SalesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('products')->group(function () {
    Route::get('/', [SalesController::class, 'products']);
    Route::post('/create', [SalesController::class, 'addProduct']);
});

Route::prefix('orders')->group(function () {
    Route::post('/', [SalesController::class, 'addOrder']);
});
Route::get('/analytics', [SalesController::class, 'getAnalytics']);
Route::get('/recommendations', [SalesController::class, 'recommendations']);

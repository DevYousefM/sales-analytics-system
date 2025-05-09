<?php

use App\Http\Controllers\Api\SalesController;
use App\Services\OpenAI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('products')->group(function () {
    Route::post('/create', [SalesController::class, 'addProduct']);
    Route::get('/get', [SalesController::class, 'products']);
});

Route::prefix('orders')->group(function () {
    Route::post('/create', [SalesController::class, 'addOrder']);
});
Route::get('/analytics', [SalesController::class, 'getAnalytics']);
Route::get('/recommendations', [SalesController::class, 'recommendations']);

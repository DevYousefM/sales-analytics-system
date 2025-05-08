<?php

namespace App\Http\Controllers;

use App\Repositories\ProductRepository;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    public function products()
    {
        $products = $this->productService->getPaginatedProducts();
        return view('products.index', compact('products'));
    }
    public function orders()
    {
        return view('orders.index');
    }
}

<?php

namespace App\Http\Controllers;

use App\Repositories\ProductRepository;
use App\Services\OrderService;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    protected ProductService $productService;
    protected OrderService $orderService;

    public function __construct(ProductService $productService, OrderService $orderService)
    {
        $this->productService = $productService;
        $this->orderService = $orderService;
    }
    public function products()
    {
        $products = $this->productService->getPaginatedProducts();
        return view('products.index', compact('products'));
    }
    public function orders()
    {
        $orders = $this->orderService->getPaginatedOrders();
        return view('orders.index', compact('orders'));
    }
    public function dashboard()
    {
        return view('dashboard');
    }
    public function addOrder()
    {
        return view('orders.create');
    }
}

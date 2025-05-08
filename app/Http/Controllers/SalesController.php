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
        $total_revenue = $this->orderService->getTotalRevenue();
        $top_products = $this->orderService->getTopProductsByQuantity();
        $revenue_change_in_last_minute = $this->orderService->getRevenueChangeInLastMinute();
        $orders_count_in_last_minute = $this->orderService->getOrdersCountInLastMinute();

        return view('dashboard', compact('total_revenue', 'top_products', 'revenue_change_in_last_minute', 'orders_count_in_last_minute'));
    }
}

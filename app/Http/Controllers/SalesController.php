<?php

namespace App\Http\Controllers;

use App\Repositories\ConfigRepository;
use App\Services\OrderService;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    protected ProductService $productService;
    protected OrderService $orderService;
    protected ConfigRepository $configRepository;

    public function __construct(ProductService $productService, OrderService $orderService, ConfigRepository $configRepository)
    {
        $this->productService = $productService;
        $this->orderService = $orderService;
        $this->configRepository = $configRepository;
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
    public function recommendations()
    {
        return view('recommendations');
    }
    public function suggestions()
    {
        $info = $this->productService->getProductsWithTemperatureInfo();

        $products = $info['products'];
        $temp = $info['temp'];
        $temp_category = $info['temp_category'];

        return view('suggestions', compact('products', 'temp', 'temp_category'));
    }
}

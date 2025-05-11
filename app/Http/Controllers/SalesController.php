<?php

namespace App\Http\Controllers;

use App\Services\ConfigService;
use App\Services\OrderService;
use App\Services\ProductService;

class SalesController extends Controller
{
    protected ProductService $productService;
    protected OrderService $orderService;
    protected ConfigService $configService;

    public function __construct(ProductService $productService, OrderService $orderService, ConfigService $configService)
    {
        $this->productService = $productService;
        $this->orderService = $orderService;
        $this->configService = $configService;
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
        $temp = $this->configService->getTemperature();
        $increment_percent = $this->configService->getIncrementPercent();
        $temp_category = $this->configService->checkTempCategory($temp);

        return view('orders.create', compact('temp', 'increment_percent', 'temp_category'));
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

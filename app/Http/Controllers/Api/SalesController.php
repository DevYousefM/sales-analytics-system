<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddOrderRequest;
use App\Http\Requests\AddProductRequest;
use App\Http\Resources\BaseResponse;
use App\Services\IntegrationWithAI;
use App\Services\OrderService;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    protected ProductService $productService;
    protected OrderService $orderService;
    protected IntegrationWithAI $integrationWithAI;
    public function __construct(ProductService $productService, OrderService $orderService, IntegrationWithAI $integrationWithAI)
    {
        $this->productService = $productService;
        $this->orderService = $orderService;
        $this->integrationWithAI = $integrationWithAI;
    }

    public function addProduct(AddProductRequest $request)
    {
        $product = $this->productService->createNewProduct($request);
        return new BaseResponse('success', 'Product added successfully', $product);
    }
    public function products()
    {
        $products = $this->productService->getProducts();
        return new BaseResponse('success', 'Products fetched successfully', $products);
    }
    public function addOrder(AddOrderRequest $request)
    {
        $order = $this->orderService->createNewOrder($request);
        return new BaseResponse('success', 'Order added successfully', $order);
    }

    public function getAnalytics()
    {
        $analytics = $this->orderService->getUpdateAnalysisEventData();
        return new BaseResponse('success', 'Analytics fetched successfully', $analytics);
    }
    public function recommendations()
    {
        return $this->integrationWithAI->getRecommendationsWithAI();
    }
}

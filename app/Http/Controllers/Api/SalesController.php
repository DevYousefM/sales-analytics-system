<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddOrderRequest;
use App\Http\Requests\AddProductRequest;
use App\Http\Resources\BaseResponse;
use App\Repositories\ConfigRepository;
use App\Services\IntegrationWithAIService;
use App\Services\OrderService;
use App\Services\ProductService;

class SalesController extends Controller
{
    protected ProductService $productService;
    protected OrderService $orderService;
    protected IntegrationWithAIService $integrationWithAIService;
    protected ConfigRepository $configRepository;
    public function __construct(ProductService $productService, OrderService $orderService, IntegrationWithAIService $integrationWithAIService, ConfigRepository $configRepository)
    {
        $this->productService = $productService;
        $this->orderService = $orderService;
        $this->integrationWithAIService = $integrationWithAIService;
        $this->configRepository = $configRepository;
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
        $response = $this->integrationWithAIService->getRecommendationsWithAI();
        if (isset($response['error'])) {
            return new BaseResponse('failed', $response['error'], []);
        }
        $productsIDs = collect($response)->pluck('product_id')->toArray();
        $products = $this->productService->getProductsByIds($productsIDs);

        sleep(1);
        return new BaseResponse('success', 'Recommendations fetched successfully', $products);
    }
}

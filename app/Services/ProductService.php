<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class ProductService
{
    protected ProductRepository $productRepository;
    protected string $cacheKeyList = 'products:cache_keys';
    protected ConfigService $configService;
    public function __construct(ProductRepository $productRepository, ConfigService $configService)
    {
        $this->productRepository = $productRepository;
        $this->configService = $configService;
    }

    public function getPaginatedProducts(): LengthAwarePaginator
    {
        $page = request()->get('page', 1);
        $perPage = request()->get('per_page', 6);

        $this->rememberProductCacheKey('products_total');
        $this->rememberProductCacheKey("products_page_{$page}");

        $result = $this->productRepository->paginate($page);

        $result = $this->paginateProducts($result["result"], $result["total"], $page, $perPage);

        return $result;
    }
    public function createNewProduct(Request $request)
    {
        $this->productRepository->insertProductToDB($request->validated());

        $this->clearProductsCache();

        $id = $this->productRepository->getLastProductID();

        $product = $this->productRepository->getProductByID($id);

        return $product[0];
    }
    protected function clearProductsCache()
    {
        $cacheKeys = Cache::get($this->cacheKeyList, []);
        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }
        Cache::forget($this->cacheKeyList);
    }
    public function rememberProductCacheKey(string $key): void
    {
        $keys = Cache::get($this->cacheKeyList, []);
        $keys[] = $key;
        $keys = array_unique($keys);
        Cache::put($this->cacheKeyList, $keys, env('CACHE_TTL', 60));
    }
    public function getProducts()
    {
        $products = $this->productRepository->getProducts();
        $this->rememberProductCacheKey('products');
        return $products;
    }
    public function getProductsByIds(array $ids)
    {
        return $this->productRepository->getProductsByIds($ids);
    }

    public function getProductsWithTemperatureInfo()
    {
        $page = request()->get('page', 1);
        $perPage = request()->get('per_page', 6);

        $temp = $this->configService->getTemperature();
        $temp_category = $this->configService->checkTempCategory($temp);

        $this->rememberProductCacheKey("products_temp_{$temp_category}");
        $this->rememberProductCacheKey("products_temp_total_{$temp_category}_{$page}");

        $result = $this->productRepository->getProductsDependingOnTemperature($temp_category, $page);

        $result = $this->paginateProducts($result['result'], $result['total'], $page, $perPage);

        return [
            'products' => $result,
            'temp' => $temp,
            'temp_category' => $temp_category
        ];
    }
    private function paginateProducts(array $result, int $total, int $page, int $perPage = 6)
    {
        $result = $this->changeVisiblePrice($result);

        return new LengthAwarePaginator(collect($result), $total, $perPage, $page, [
            'path' => request()->url(),
            'query' => request()->query(),
        ]);
    }
    private function changeVisiblePrice(array $products)
    {
        $temp = $this->configService->getTemperature();
        $temp_category = $this->configService->checkTempCategory($temp);

        $products = array_map(function ($product) use ($temp_category) {
            if ($product->temp_category == $temp_category) {
                $product->old_price = $product->price;
                $price_after_calc = $this->calculateProductPrice($product->price);
                $formatted_price = number_format($price_after_calc, 2);
                $product->price = doubleval($formatted_price);
                return $product;
            } else {
                return $product;
            }
        }, $products);

        return $products;
    }

    public function calculateProductPrice($price)
    {
        $increment_percent = $this->configService->getIncrementPercent();
        return $price + ($price * $increment_percent / 100);
    }
}

<?php

namespace App\Services;

use App\Repositories\ConfigRepository;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use PSpell\Config;

class ProductService
{
    protected ProductRepository $productRepository;
    protected string $cacheKeyList = 'products:cache_keys';
    protected ConfigRepository $configRepository;
    public function __construct(ProductRepository $productRepository, ConfigRepository $configRepository)
    {
        $this->productRepository = $productRepository;
        $this->configRepository = $configRepository;
    }

    public function getPaginatedProducts(): LengthAwarePaginator
    {
        $page = request()->get('page', 1);

        $this->rememberProductCacheKey('products_total');
        $this->rememberProductCacheKey("products_page_{$page}");

        return $this->productRepository->paginate($page);
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

        $temp = $this->configRepository->getTemperature();
        $temp_category = $this->productRepository->checkTempCategory($temp);

        $this->rememberProductCacheKey("products_temp_{$temp_category}");
        $this->rememberProductCacheKey("products_temp_total_{$temp_category}_{$page}");

        $result = $this->productRepository->getProductsDependingOnTemperature($temp_category, $page);

        return [
            'products' => $result,
            'temp' => $temp,
            'temp_category' => $temp_category
        ];
    }

}

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

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getPaginatedProducts(): LengthAwarePaginator
    {
        $page = request()->get('page', 1);

        $this->rememberProductCacheKey('total');
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
}

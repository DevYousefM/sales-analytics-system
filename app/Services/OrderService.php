<?php

namespace App\Services;

use App\Repositories\OrderRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class OrderService
{
    protected $orderRepository;
    protected $cacheKeyList = 'orders:cache_keys';

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function getPaginatedOrders()
    {
        $page = request()->get('page', 1);

        $this->rememberOrderCacheKey('total_orders');
        $this->rememberOrderCacheKey('orders_page_' . $page);

        return $this->orderRepository->paginate($page);
    }

    public function createNewOrder(Request $request)
    {
        $this->orderRepository->insertOrderToDB($request->validated());

        $this->clearOrdersCache();

        $id = $this->orderRepository->getLastOrderID();

        $order = $this->orderRepository->getOrderByID($id);

        return $order[0];
    }

    public function clearOrdersCache()
    {
        $cacheKeys = Cache::get($this->cacheKeyList, []);
        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }
        Cache::forget($this->cacheKeyList);
    }

    public function rememberOrderCacheKey(string $key): void
    {
        $keys = Cache::get($this->cacheKeyList, []);
        $keys[] = $key;
        $keys = array_unique($keys);
        Cache::put($this->cacheKeyList, $keys, env('CACHE_TTL', 60));
    }
}

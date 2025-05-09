<?php

namespace App\Services;

use App\Events\UpdateAnalyticsEvent;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use stdClass;

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

        $this->dispatchUpdateAnalyticsEvent();

        return $order;
    }

    public function dispatchUpdateAnalyticsEvent()
    {
        $data = [
            'total_revenue' => $this->getTotalRevenue(),
            'orders_count_in_last_minute' => $this->getOrdersCountInLastMinute(),
            'revenue_change_in_last_minute' => $this->getRevenueChangeInLastMinute(),
            'top_products_by_quantity' => $this->getTopProductsByQuantity(),
        ];

        event(new UpdateAnalyticsEvent($data));
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

    public function getTotalRevenue(): int
    {
        return $this->orderRepository->totalRevenue() ?? 0;
    }
    public function getTopProductsByQuantity(): array
    {
        return $this->orderRepository->topProductsByQuantity() ?? [];
    }
    public function getRevenueChangeInLastMinute()
    {
        return $this->orderRepository->revenueChangeInLastMinute()->absolute_change ?? 0;
    }
    public function getOrdersCountInLastMinute(): int
    {
        return $this->orderRepository->ordersCountInLastMinute() ?? 0;
    }
}

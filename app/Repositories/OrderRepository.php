<?php

namespace App\Repositories;

use App\Services\ConfigService;
use App\Services\ProductService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class OrderRepository
{
    protected $productRepository;
    protected $configService;
    protected $productService;

    public function __construct(ProductRepository $productRepository, ConfigService $configService, ProductService $productService)
    {
        $this->productRepository = $productRepository;
        $this->configService = $configService;
        $this->productService = $productService;
    }

    public function paginate(int $page = 1, int $perPage = 6)
    {
        $offset = ($page - 1) * $perPage;

        $total = Cache::remember('total_orders', env('CACHE_TTL', 60), function () {
            return DB::table('orders')->count();
        });

        $result = Cache::remember("orders_page_{$page}", env('CACHE_TTL', 60), function () use ($offset, $perPage) {
            return DB::select('
                SELECT orders.id,orders.price,orders.quantity,orders.date, orders.product_id, orders.created_at,
                products.name AS product_name,
                products.price AS product_price,
                products.description AS product_description
                FROM orders
                JOIN products ON products.id = orders.product_id
                ORDER BY orders.created_at DESC
                LIMIT ? OFFSET ?', [$perPage, $offset]);
        });

        return [
            'total' => $total,
            'data' => $result
        ];
    }


    public function insertOrderToDB($data)
    {
        $product_id = $data['product'];
        $quantity = $data['quantity'];
        $product = $this->productRepository->getProductByID($product_id);

        $price = $product[0]->price * $quantity;

        $temp = $this->configService->getTemperature();
        $temp_category = $this->configService->checkTempCategory($temp);

        if ($temp_category != $product[0]->temp_category) {
            $price = $this->productService->calculateProductPrice($price);
        }

        DB::select('INSERT INTO orders (product_id, quantity, price, date, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?)', [$product_id, $quantity, $price, now(), now(), now()]);
    }

    public function getLastOrderID()
    {
        return DB::getPdo()->lastInsertId();
    }

    public function getOrderByID($id)
    {
        $order = DB::select('
        SELECT orders.id,orders.price,orders.quantity,orders.date, orders.product_id, orders.created_at,
        products.name AS product_name,
        products.price AS product_price,
        products.description AS product_description
        FROM orders
        JOIN products ON products.id = orders.product_id
        WHERE orders.id = ?
        ', [$id]);
        return $this->mapOnOrder($order[0]);
    }
    private function mapOnOrder($order)
    {
        return (object)[
            'id' => $order->id,
            'product' => (object)[
                'id' => $order->product_id,
                'name' => $order->product_name,
                'price' => $order->product_price,
                'description' => $order->product_description
            ],
            'product_id' => $order->product_id,
            'quantity' => $order->quantity,
            'price' => $order->price,
            'date' => $order->date,
        ];
    }
    public function totalRevenue()
    {
        return DB::select('SELECT SUM(price) AS total_revenue FROM orders')[0]->total_revenue;
    }
    public function topProductsByQuantity()
    {
        return DB::select('
        SELECT
            orders.product_id,
            products.name AS product_name,
            SUM(quantity) AS total_quantity
        FROM orders
        JOIN products ON products.id = orders.product_id
        GROUP BY products.name, orders.product_id
        ORDER BY total_quantity DESC
        LIMIT 5');
    }
    public function revenueChangeInLastMinute()
    {
        return DB::selectOne('
            SELECT
                SUM(CASE WHEN strftime("%Y-%m-%d %H:%M", created_at) = strftime("%Y-%m-%d %H:%M", "now") THEN price ELSE 0 END) -
                SUM(CASE WHEN strftime("%Y-%m-%d %H:%M", created_at) = strftime("%Y-%m-%d %H:%M", "now", "-1 minute") THEN price ELSE 0 END) AS absolute_change
            FROM orders
        ');
    }
    public function ordersCountInLastMinute()
    {
        return DB::select('
            SELECT COUNT(orders.id) AS order_count
                FROM orders
                WHERE created_at >= DATETIME("now", "-1 minute")
        ')[0]->order_count;
    }


    public function getOrderSentToAI()
    {
        return DB::select('
                SELECT orders.id,orders.price,orders.quantity,orders.date, orders.product_id, orders.created_at
                FROM orders
                ORDER BY orders.created_at DESC
                LIMIT 15');
    }
}

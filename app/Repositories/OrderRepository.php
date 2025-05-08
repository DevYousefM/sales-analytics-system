<?php

namespace App\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class OrderRepository
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function paginate(int $page = 1, int $perPage = 6): LengthAwarePaginator
    {

        $offset = ($page - 1) * $perPage;

        $total = Cache::remember('total_orders', env('CACHE_TTL', 60), function () {
            return DB::table('orders')->count();
        });

        $items = Cache::remember("orders_page_{$page}", env('CACHE_TTL', 60), function () use ($offset, $perPage) {
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

        return new LengthAwarePaginator($this->mapOnOrders($items), $total, $perPage, $page, [
            'path' => request()->url(),
            'query' => request()->query(),
        ]);
    }

    private function mapOnOrders($orders)
    {
        return collect($orders)->map(function ($order) {
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
        });
    }

    public function insertOrderToDB($data)
    {
        $product_id = $data['product'];
        $quantity = $data['quantity'];
        $product = $this->productRepository->getProductByID($product_id);
        $price = $product[0]->price * $quantity;

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
    public function mapOnOrder($order)
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
}

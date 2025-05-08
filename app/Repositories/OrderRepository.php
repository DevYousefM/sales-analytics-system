<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class OrderRepository
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
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
        return DB::select('SELECT * FROM orders WHERE id = ?', [$id]);
    }
}

<?php

namespace App\Repositories;

use App\Services\ProductService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ProductRepository
{
    public function paginate(int $page = 1, int $perPage = 6): LengthAwarePaginator
    {
        $offset = ($page - 1) * $perPage;

        $total = Cache::remember('products_total', env('CACHE_TTL', 60), function () {
            return DB::table('products')->count();
        });

        $items = Cache::remember("products_page_{$page}", env('CACHE_TTL', 60), function () use ($offset, $perPage) {
            return DB::select('SELECT * FROM products ORDER BY created_at DESC LIMIT ? OFFSET ?', [$perPage, $offset]);
        });

        return new LengthAwarePaginator(collect($items), $total, $perPage, $page, [
            'path' => request()->url(),
            'query' => request()->query(),
        ]);
    }
    public function insertProductToDB($data)
    {
        $name = $data['name'];
        $price = $data['price'];
        $description = $data['description'];

        DB::insert('INSERT INTO products (name, price, description, created_at, updated_at) VALUES (?, ?, ?, ?, ?)', [$name, $price, $description, now(), now()]);
    }
    public function getLastProductID()
    {
        return DB::getPdo()->lastInsertId();
    }
    public function getProductByID($id)
    {
        return DB::select('SELECT * FROM products WHERE id = ?', [$id]);
    }
    public function getProducts()
    {
        return Cache::remember('products', env('CACHE_TTL', 60), function () {
            return DB::select('SELECT * FROM products');
        });
    }
}

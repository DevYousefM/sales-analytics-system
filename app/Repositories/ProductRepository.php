<?php

namespace App\Repositories;

use App\Enum\TempCategoryEnum;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductRepository
{
    protected $configRepository;
    public function __construct(ConfigRepository $configRepository)
    {
        $this->configRepository = $configRepository;
    }
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
        $temp_category = $data['temp_category'];

        DB::insert('INSERT INTO products (name, price, description, created_at, updated_at, temp_category) VALUES (?, ?, ?, ?, ?, ?)', [$name, $price, $description, now(), now(), $temp_category]);
    }
    public function getLastProductID()
    {
        return DB::getPdo()->lastInsertId();
    }
    public function getProductByID($id)
    {
        return DB::select('SELECT * FROM products WHERE id = ?', [$id]);
    }
    public function getProductsByIds(array $ids)
    {
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        return DB::select('SELECT * FROM products WHERE id IN (' . $placeholders . ')', $ids);
    }
    public function getProducts()
    {
        return Cache::remember('products', env('CACHE_TTL', 60), function () {
            return DB::select('SELECT * FROM products ORDER BY created_at DESC');
        });
    }

    public function checkTempCategory(int $temp)
    {
        if ($temp >= 30) {
            return TempCategoryEnum::HOT->value;
        } else {
            return TempCategoryEnum::COLD->value;
        }
    }

    public function getProductsDependingOnTemperature($temp_category, $page, $perPage = 6)
    {
        $offset = ($page - 1) * $perPage;

        $total = Cache::remember("products_temp_total_{$temp_category}", env('CACHE_TTL', 60), function () use ($temp_category) {
            return DB::select("SELECT COUNT(*) AS total FROM products WHERE temp_category = ?", [$temp_category])[0]->total;
        });

        $result = Cache::remember("products_temp_{$temp_category}_{$page}", env('CACHE_TTL', 60), function () use ($temp_category, $offset, $perPage) {
            return DB::select("SELECT * FROM products WHERE temp_category = ? ORDER BY created_at DESC LIMIT ? OFFSET ?", [$temp_category, $perPage, $offset]);
        });

        return new LengthAwarePaginator(collect($result), $total, $perPage, $page, [
            'path' => request()->url(),
            'query' => request()->query(),
        ]);
    }
}

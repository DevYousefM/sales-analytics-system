<?php

namespace App\Repositories;

use App\Enum\TempCategoryEnum;
use App\Services\ConfigService;
use App\Services\ProductService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductRepository
{
    protected $configService;
    public function __construct(ConfigService $configService)
    {
        $this->configService = $configService;
    }
    public function paginate(int $page = 1, int $perPage = 6)
    {
        $offset = ($page - 1) * $perPage;

        $total = Cache::remember('products_total', env('CACHE_TTL', 60), function () {
            return DB::table('products')->count();
        });

        $result = Cache::remember("products_page_{$page}", env('CACHE_TTL', 60), function () use ($offset, $perPage) {
            return DB::select('SELECT * FROM products ORDER BY created_at DESC LIMIT ? OFFSET ?', [$perPage, $offset]);
        });

        return [
            "total" => $total,
            "result" => $result
        ];
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

    public function getProductsDependingOnTemperature($temp_category, $page, $perPage = 6)
    {
        $offset = ($page - 1) * $perPage;

        $_temp_category = $temp_category == TempCategoryEnum::HOT ? TempCategoryEnum::COLD->value : TempCategoryEnum::HOT->value;

        $total = Cache::remember("products_temp_total_{$temp_category}", env('CACHE_TTL', 60), function () use ($_temp_category) {
            return DB::select("SELECT COUNT(*) AS total FROM products WHERE temp_category = ?", [$_temp_category])[0]->total;
        });

        $result = Cache::remember("products_temp_{$temp_category}_{$page}", env('CACHE_TTL', 60), function () use ($_temp_category, $offset, $perPage) {
            return DB::select("SELECT * FROM products WHERE temp_category = ? ORDER BY created_at DESC LIMIT ? OFFSET ?", [$_temp_category, $perPage, $offset]);
        });

        return [
            "total" => $total,
            "result" => $result
        ];
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = [
            [
                'product_id' => 1,
                'quantity' => 1,
                'price' => 100,
                'date' => '2023-05-08',
            ],
            [
                'product_id' => 2,
                'quantity' => 2,
                'price' => 200,
                'date' => '2023-05-08',
            ],
            [
                'product_id' => 3,
                'quantity' => 3,
                'price' => 300,
                'date' => '2023-05-08',
            ],
            [
                'product_id' => 4,
                'quantity' => 4,
                'price' => 400,
                'date' => '2023-05-08',
            ],
            [
                'product_id' => 5,
                'quantity' => 5,
                'price' => 500,
                'date' => '2023-05-08',
            ],
        ];

        foreach ($orders as $order) {
            DB::insert("INSERT INTO orders (product_id, quantity, price, date, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?)", [$order['product_id'], $order['quantity'], $order['price'], $order['date'], now(), now()]);
        }
    }
}

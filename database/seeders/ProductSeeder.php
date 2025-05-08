<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->delete();
        $products = [
            [
                'name' => 'Classic Margherita Pizza',
                'description' => 'A simple pizza with mozzarella, tomatoes, and fresh basil.',
                'price' => 8.99
            ],
            [
                'name' => 'Pepperoni Pizza',
                'description' => 'Mozzarella, pepperoni, and tomato sauce.',
                'price' => 10.99
            ],
            [
                'name' => 'Veggie Burger',
                'description' => 'A plant-based burger with lettuce, tomato, and vegan mayo.',
                'price' => 6.99
            ],
            [
                'name' => 'BLT Sandwich',
                'description' => 'Bacon, lettuce, tomato, and mayonnaise on toasted bread.',
                'price' => 5.99
            ],
            [
                'name' => 'Spaghetti Bolognese',
                'description' => 'Classic pasta with a rich beef and tomato sauce.',
                'price' => 12.49
            ],
            [
                'name' => 'Chocolate Lava Cake',
                'description' => 'A warm chocolate cake with a gooey molten center.',
                'price' => 4.99
            ],
            [
                'name' => 'Coca-Cola',
                'description' => 'A refreshing carbonated soft drink.',
                'price' => 1.99
            ],
            [
                'name' => 'Orange Juice',
                'description' => 'Freshly squeezed orange juice.',
                'price' => 2.49
            ],
            [
                'name' => 'Iced Latte',
                'description' => 'Chilled espresso mixed with cold milk and ice.',
                'price' => 3.99
            ],
            [
                'name' => 'Berry Blast Smoothie',
                'description' => 'A refreshing blend of strawberries, blueberries, and yogurt.',
                'price' => 5.49
            ],
            [
                'name' => 'Margarita',
                'description' => 'A classic cocktail with tequila, lime juice, and triple sec.',
                'price' => 7.99
            ],
            [
                'name' => 'Chocolate Milkshake',
                'description' => 'Creamy milkshake with chocolate syrup and whipped cream.',
                'price' => 3.99
            ]
        ];

        foreach ($products as $product) {
            DB::insert('INSERT INTO products (name, description, price, created_at, updated_at) VALUES (?, ?, ?, ?, ?)', [$product['name'], $product['description'], $product['price'], now(), now()]);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Enum\TempCategoryEnum;
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
                'name' => 'Coca-Cola',
                'description' => 'A refreshing carbonated soft drink.',
                'price' => 1.99,
                'temp_category' => TempCategoryEnum::COLD->value
            ],
            [
                'name' => 'Orange Juice',
                'description' => 'Freshly squeezed orange juice.',
                'price' => 2.49,
                'temp_category' => TempCategoryEnum::COLD->value
            ],
            [
                'name' => 'Iced Latte',
                'description' => 'Chilled espresso mixed with cold milk and ice.',
                'price' => 3.99,
                'temp_category' => TempCategoryEnum::COLD->value
            ],
            [
                'name' => 'Berry Blast Smoothie',
                'description' => 'A refreshing blend of strawberries, blueberries, and yogurt.',
                'price' => 5.49,
                'temp_category' => TempCategoryEnum::COLD->value
            ],
            [
                'name' => 'Margarita',
                'description' => 'A classic cocktail with tequila, lime juice, and triple sec.',
                'price' => 7.99,
                'temp_category' => TempCategoryEnum::COLD->value
            ],
            [
                'name' => 'Chocolate Milkshake',
                'description' => 'Creamy milkshake with chocolate syrup and whipped cream.',
                'price' => 3.99,
                'temp_category' => TempCategoryEnum::COLD->value
            ],
            [
                'name' => 'Espresso',
                'description' => 'Strong and rich hot coffee shot.',
                'price' => 2.49,
                'temp_category' => TempCategoryEnum::HOT->value
            ],
            [
                'name' => 'Green Tea',
                'description' => 'Hot brewed green tea with antioxidants.',
                'price' => 1.99,
                'temp_category' => TempCategoryEnum::HOT->value
            ],
            [
                'name' => 'Americano',
                'description' => 'Hot water mixed with a shot of espresso.',
                'price' => 2.79,
                'temp_category' => TempCategoryEnum::HOT->value
            ],
            [
                'name' => 'Hot Chocolate',
                'description' => 'Warm milk with melted chocolate and whipped cream.',
                'price' => 3.49,
                'temp_category' => TempCategoryEnum::HOT->value
            ],
            [
                'name' => 'Lemon Iced Tea',
                'description' => 'Cold black tea with lemon flavor.',
                'price' => 2.29,
                'temp_category' => TempCategoryEnum::COLD->value
            ],
            [
                'name' => 'Mocha',
                'description' => 'Hot espresso blended with chocolate and steamed milk.',
                'price' => 3.89,
                'temp_category' => TempCategoryEnum::HOT->value
            ],
            [
                'name' => 'Vanilla Frappe',
                'description' => 'Blended iced coffee with vanilla flavor.',
                'price' => 4.29,
                'temp_category' => TempCategoryEnum::COLD->value
            ],
            [
                'name' => 'Cappuccino',
                'description' => 'Espresso with steamed milk foam.',
                'price' => 3.59,
                'temp_category' => TempCategoryEnum::HOT->value
            ],
            [
                'name' => 'Strawberry Lemonade',
                'description' => 'Iced lemonade with strawberry syrup.',
                'price' => 2.99,
                'temp_category' => TempCategoryEnum::COLD->value
            ],
        ];

        foreach ($products as $product) {
            DB::insert('INSERT INTO products (name, description, price, created_at, updated_at, temp_category) VALUES (?, ?, ?, ?, ?, ?)', [$product['name'], $product['description'], $product['price'], now(), now(), $product['temp_category']]);
        }
    }
}

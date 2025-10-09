<?php

namespace Database\Seeders;

use App\Models\CafeCategory;
use App\Models\CafeProduct;
use Illuminate\Database\Seeder;

class CafeDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create Categories
        $categories = [
            [
                'name' => 'Hot Drinks',
                'slug' => 'hot-drinks',
                'description' => 'Fresh brewed coffee, tea, and hot chocolate',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Cold Drinks',
                'slug' => 'cold-drinks',
                'description' => 'Refreshing cold beverages and iced drinks',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Pastries & Cakes',
                'slug' => 'pastries-cakes',
                'description' => 'Freshly baked pastries, cakes, and sweet treats',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Sandwiches',
                'slug' => 'sandwiches',
                'description' => 'Fresh sandwiches and wraps',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Light Meals',
                'slug' => 'light-meals',
                'description' => 'Soups, salads, and light lunch options',
                'sort_order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $categoryData) {
            $category = CafeCategory::updateOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData
            );

            // Add products for each category
            $this->createProductsForCategory($category);
        }
    }

    private function createProductsForCategory(CafeCategory $category): void
    {
        $products = [];

        switch ($category->slug) {
            case 'hot-drinks':
                $products = [
                    [
                        'name' => 'Americano',
                        'description' => 'Rich espresso with hot water',
                        'price' => 2.50,
                        'size' => 'medium',
                        'temperature' => 'hot',
                        'preparation_time' => 3,
                        'dietary_info' => ['vegan'],
                    ],
                    [
                        'name' => 'Cappuccino',
                        'description' => 'Espresso with steamed milk and foam',
                        'price' => 3.20,
                        'size' => 'medium',
                        'temperature' => 'hot',
                        'preparation_time' => 4,
                        'dietary_info' => ['vegetarian'],
                    ],
                    [
                        'name' => 'Latte',
                        'description' => 'Espresso with steamed milk',
                        'price' => 3.50,
                        'size' => 'large',
                        'temperature' => 'hot',
                        'preparation_time' => 4,
                        'dietary_info' => ['vegetarian'],
                    ],
                    [
                        'name' => 'English Breakfast Tea',
                        'description' => 'Traditional black tea blend',
                        'price' => 2.00,
                        'temperature' => 'hot',
                        'preparation_time' => 5,
                        'dietary_info' => ['vegan'],
                    ],
                    [
                        'name' => 'Hot Chocolate',
                        'description' => 'Rich and creamy hot chocolate',
                        'price' => 3.00,
                        'temperature' => 'hot',
                        'preparation_time' => 3,
                        'dietary_info' => ['vegetarian'],
                    ],
                ];
                break;

            case 'cold-drinks':
                $products = [
                    [
                        'name' => 'Iced Coffee',
                        'description' => 'Chilled coffee over ice',
                        'price' => 2.80,
                        'size' => 'large',
                        'temperature' => 'cold',
                        'preparation_time' => 2,
                        'dietary_info' => ['vegan'],
                    ],
                    [
                        'name' => 'Fresh Orange Juice',
                        'description' => 'Freshly squeezed orange juice',
                        'price' => 3.50,
                        'size' => 'medium',
                        'temperature' => 'cold',
                        'preparation_time' => 2,
                        'dietary_info' => ['vegan'],
                    ],
                    [
                        'name' => 'Sparkling Water',
                        'description' => 'Refreshing sparkling water',
                        'price' => 1.50,
                        'size' => 'medium',
                        'temperature' => 'cold',
                        'preparation_time' => 1,
                        'dietary_info' => ['vegan'],
                    ],
                ];
                break;

            case 'pastries-cakes':
                $products = [
                    [
                        'name' => 'Chocolate Brownie',
                        'description' => 'Rich and fudgy chocolate brownie',
                        'price' => 2.80,
                        'temperature' => 'room_temp',
                        'preparation_time' => 1,
                        'dietary_info' => ['vegetarian'],
                        'ingredients' => 'chocolate, butter, eggs, flour, sugar',
                    ],
                    [
                        'name' => 'Blueberry Muffin',
                        'description' => 'Fresh baked muffin with blueberries',
                        'price' => 2.50,
                        'temperature' => 'room_temp',
                        'preparation_time' => 1,
                        'dietary_info' => ['vegetarian'],
                        'ingredients' => 'flour, blueberries, eggs, milk, sugar',
                    ],
                    [
                        'name' => 'Carrot Cake Slice',
                        'description' => 'Moist carrot cake with cream cheese frosting',
                        'price' => 3.50,
                        'temperature' => 'room_temp',
                        'preparation_time' => 1,
                        'dietary_info' => ['vegetarian'],
                        'ingredients' => 'carrots, flour, eggs, cream cheese, walnuts',
                    ],
                ];
                break;

            case 'sandwiches':
                $products = [
                    [
                        'name' => 'Ham & Cheese Toastie',
                        'description' => 'Grilled sandwich with ham and melted cheese',
                        'price' => 4.50,
                        'temperature' => 'hot',
                        'preparation_time' => 8,
                        'ingredients' => 'bread, ham, cheese, butter',
                    ],
                    [
                        'name' => 'BLT Sandwich',
                        'description' => 'Bacon, lettuce, and tomato on fresh bread',
                        'price' => 5.00,
                        'temperature' => 'room_temp',
                        'preparation_time' => 5,
                        'ingredients' => 'bread, bacon, lettuce, tomato, mayo',
                    ],
                    [
                        'name' => 'Tuna Mayo Sandwich',
                        'description' => 'Tuna salad with mayo on white bread',
                        'price' => 4.20,
                        'temperature' => 'room_temp',
                        'preparation_time' => 3,
                        'ingredients' => 'bread, tuna, mayonnaise, cucumber',
                    ],
                ];
                break;

            case 'light-meals':
                $products = [
                    [
                        'name' => 'Tomato Soup',
                        'description' => 'Creamy tomato soup served with bread roll',
                        'price' => 4.50,
                        'temperature' => 'hot',
                        'preparation_time' => 10,
                        'dietary_info' => ['vegetarian'],
                        'ingredients' => 'tomatoes, cream, onions, herbs',
                    ],
                    [
                        'name' => 'Caesar Salad',
                        'description' => 'Fresh romaine lettuce with Caesar dressing',
                        'price' => 5.50,
                        'temperature' => 'cold',
                        'preparation_time' => 5,
                        'dietary_info' => ['vegetarian'],
                        'ingredients' => 'lettuce, parmesan, croutons, caesar dressing',
                    ],
                    [
                        'name' => 'Jacket Potato with Beans',
                        'description' => 'Baked potato served with baked beans',
                        'price' => 4.00,
                        'temperature' => 'hot',
                        'preparation_time' => 15,
                        'dietary_info' => ['vegetarian', 'vegan'],
                        'ingredients' => 'potato, baked beans, butter',
                    ],
                ];
                break;
        }

        foreach ($products as $productData) {
            $productData['category_id'] = $category->id;
            $productData['slug'] = \Illuminate\Support\Str::slug($productData['name']);
            $productData['is_available'] = true;
            $productData['sort_order'] = 0;

            CafeProduct::updateOrCreate(
                ['slug' => $productData['slug'], 'category_id' => $category->id],
                $productData
            );
        }
    }
}

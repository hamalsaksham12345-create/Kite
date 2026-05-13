<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Restaurant;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first restaurant (or create one for testing)
        $restaurant = Restaurant::first();
        
        if (!$restaurant) {
            $restaurant = Restaurant::create([
                'name' => 'Demo Restaurant',
                'slug' => 'demo-restaurant',
                'email' => 'demo@restaurant.com',
                'phone' => '555-0123',
                'address' => '123 Main St, City, State',
                'primary_color' => '#10b981',
                'secondary_color' => '#065f46',
                'is_active' => true,
                'is_verified' => true,
                'subscription_expires_at' => now()->addYear(),
                'subscription_plan' => 'premium',
                'subscription_amount' => 99.99,
                'verified_at' => now(),
            ]);
        }

        // Create categories
        $categories = [
            [
                'name' => 'Appetizers',
                'description' => 'Start your meal with our delicious appetizers',
                'sort_order' => 1,
            ],
            [
                'name' => 'Main Courses',
                'description' => 'Hearty and satisfying main dishes',
                'sort_order' => 2,
            ],
            [
                'name' => 'Desserts',
                'description' => 'Sweet endings to your perfect meal',
                'sort_order' => 3,
            ],
            [
                'name' => 'Beverages',
                'description' => 'Refreshing drinks and specialty beverages',
                'sort_order' => 4,
            ],
        ];

        foreach ($categories as $categoryData) {
            $category = Category::create([
                'restaurant_id' => $restaurant->id,
                'name' => $categoryData['name'],
                'description' => $categoryData['description'],
                'sort_order' => $categoryData['sort_order'],
                'is_active' => true,
            ]);

            // Create menu items for each category
            $this->createMenuItemsForCategory($category);
        }
    }

    private function createMenuItemsForCategory(Category $category): void
    {
        $menuItems = [];

        switch ($category->name) {
            case 'Appetizers':
                $menuItems = [
                    [
                        'name' => 'Crispy Calamari',
                        'description' => 'Fresh squid rings served with marinara sauce and lemon',
                        'price' => 12.99,
                        'ingredients' => ['squid', 'flour', 'marinara sauce', 'lemon'],
                        'allergens' => ['gluten', 'seafood'],
                        'preparation_time' => 15,
                        'is_featured' => true,
                    ],
                    [
                        'name' => 'Buffalo Wings',
                        'description' => 'Spicy chicken wings with blue cheese dip and celery sticks',
                        'price' => 14.99,
                        'ingredients' => ['chicken wings', 'buffalo sauce', 'blue cheese', 'celery'],
                        'allergens' => ['dairy'],
                        'preparation_time' => 20,
                    ],
                    [
                        'name' => 'Spinach Artichoke Dip',
                        'description' => 'Creamy dip served with tortilla chips',
                        'price' => 10.99,
                        'ingredients' => ['spinach', 'artichoke', 'cream cheese', 'tortilla chips'],
                        'allergens' => ['dairy', 'gluten'],
                        'preparation_time' => 10,
                    ],
                ];
                break;

            case 'Main Courses':
                $menuItems = [
                    [
                        'name' => 'Grilled Salmon',
                        'description' => 'Atlantic salmon with lemon herb butter and seasonal vegetables',
                        'price' => 24.99,
                        'ingredients' => ['salmon', 'lemon', 'herbs', 'butter', 'vegetables'],
                        'allergens' => ['fish', 'dairy'],
                        'preparation_time' => 25,
                        'is_featured' => true,
                    ],
                    [
                        'name' => 'Ribeye Steak',
                        'description' => '12oz ribeye cooked to perfection with garlic mashed potatoes',
                        'price' => 32.99,
                        'ingredients' => ['ribeye steak', 'garlic', 'potatoes', 'butter'],
                        'allergens' => ['dairy'],
                        'preparation_time' => 30,
                    ],
                    [
                        'name' => 'Chicken Parmesan',
                        'description' => 'Breaded chicken breast with marinara and mozzarella over pasta',
                        'price' => 19.99,
                        'ingredients' => ['chicken breast', 'breadcrumbs', 'marinara', 'mozzarella', 'pasta'],
                        'allergens' => ['gluten', 'dairy'],
                        'preparation_time' => 25,
                    ],
                    [
                        'name' => 'Vegetarian Pasta',
                        'description' => 'Penne pasta with roasted vegetables and pesto sauce',
                        'price' => 16.99,
                        'ingredients' => ['penne pasta', 'zucchini', 'bell peppers', 'pesto', 'parmesan'],
                        'allergens' => ['gluten', 'dairy', 'nuts'],
                        'preparation_time' => 20,
                    ],
                ];
                break;

            case 'Desserts':
                $menuItems = [
                    [
                        'name' => 'Chocolate Lava Cake',
                        'description' => 'Warm chocolate cake with molten center and vanilla ice cream',
                        'price' => 8.99,
                        'ingredients' => ['chocolate', 'flour', 'eggs', 'vanilla ice cream'],
                        'allergens' => ['gluten', 'dairy', 'eggs'],
                        'preparation_time' => 15,
                        'is_featured' => true,
                    ],
                    [
                        'name' => 'Tiramisu',
                        'description' => 'Classic Italian dessert with coffee-soaked ladyfingers',
                        'price' => 7.99,
                        'ingredients' => ['mascarpone', 'coffee', 'ladyfingers', 'cocoa'],
                        'allergens' => ['gluten', 'dairy', 'eggs'],
                        'preparation_time' => 5,
                    ],
                    [
                        'name' => 'New York Cheesecake',
                        'description' => 'Rich and creamy cheesecake with berry compote',
                        'price' => 6.99,
                        'ingredients' => ['cream cheese', 'graham crackers', 'berries', 'sugar'],
                        'allergens' => ['gluten', 'dairy', 'eggs'],
                        'preparation_time' => 5,
                    ],
                ];
                break;

            case 'Beverages':
                $menuItems = [
                    [
                        'name' => 'Fresh Lemonade',
                        'description' => 'House-made lemonade with fresh lemons',
                        'price' => 3.99,
                        'ingredients' => ['lemons', 'sugar', 'water', 'ice'],
                        'allergens' => [],
                        'preparation_time' => 5,
                    ],
                    [
                        'name' => 'Craft Beer Selection',
                        'description' => 'Ask your server about our rotating craft beer selection',
                        'price' => 5.99,
                        'ingredients' => ['hops', 'malt', 'yeast'],
                        'allergens' => ['gluten'],
                        'preparation_time' => 2,
                    ],
                    [
                        'name' => 'House Wine',
                        'description' => 'Red or white wine by the glass',
                        'price' => 7.99,
                        'ingredients' => ['grapes'],
                        'allergens' => ['sulfites'],
                        'preparation_time' => 2,
                    ],
                ];
                break;
        }

        foreach ($menuItems as $index => $itemData) {
            MenuItem::create([
                'restaurant_id' => $category->restaurant_id,
                'category_id' => $category->id,
                'name' => $itemData['name'],
                'description' => $itemData['description'],
                'price' => $itemData['price'],
                'ingredients' => $itemData['ingredients'],
                'allergens' => $itemData['allergens'],
                'preparation_time' => $itemData['preparation_time'],
                'sort_order' => $index + 1,
                'is_available' => true,
                'is_featured' => $itemData['is_featured'] ?? false,
            ]);
        }
    }
}
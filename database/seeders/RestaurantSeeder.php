<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class RestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample restaurants with different statuses
        
        // 1. Active Restaurant
        $restaurant1 = Restaurant::firstOrCreate(
            ['slug' => 'marios-pizza'],
            [
                'name' => 'Mario\'s Pizza Palace',
                'email' => 'mario@example.com',
                'phone' => '+1 (555) 123-4567',
                'address' => '123 Main Street, New York, NY 10001',
                'is_active' => true,
                'is_verified' => true,
                'subscription_plan' => 'semi_annual',
                'subscription_amount' => 149.99,
                'subscription_expires_at' => Carbon::now()->addMonths(6),
                'verified_at' => Carbon::now()->subDays(30),
            ]
        );

        // Create owner for Mario's Pizza
        User::firstOrCreate(
            ['email' => 'mario@example.com'],
            [
                'name' => 'Mario Rossi',
                'password' => Hash::make('password'),
                'restaurant_id' => $restaurant1->id,
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        // Create staff for Mario's Pizza
        User::firstOrCreate(
            ['email' => 'luigi@example.com'],
            [
                'name' => 'Luigi Rossi',
                'password' => Hash::make('password'),
                'restaurant_id' => $restaurant1->id,
                'role' => 'waiter',
                'is_active' => true,
            ]
        );

        User::firstOrCreate(
            ['email' => 'giuseppe@example.com'],
            [
                'name' => 'Giuseppe Chef',
                'password' => Hash::make('password'),
                'restaurant_id' => $restaurant1->id,
                'role' => 'chef',
                'is_active' => true,
            ]
        );

        // 2. Pending Restaurant
        $restaurant2 = Restaurant::firstOrCreate(
            ['slug' => 'sakura-sushi'],
            [
                'name' => 'Sakura Sushi Bar',
                'email' => 'owner@sakurasushi.com',
                'phone' => '+1 (555) 987-6543',
                'address' => '456 Oak Avenue, Los Angeles, CA 90210',
                'is_active' => false,
                'is_verified' => false,
                'subscription_plan' => 'monthly',
                'subscription_amount' => 29.99,
            ]
        );

        // Create owner for Sakura Sushi
        User::firstOrCreate(
            ['email' => 'owner@sakurasushi.com'],
            [
                'name' => 'Yuki Tanaka',
                'password' => Hash::make('password'),
                'restaurant_id' => $restaurant2->id,
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        // 3. Another Active Restaurant
        $restaurant3 = Restaurant::firstOrCreate(
            ['slug' => 'burger-joint'],
            [
                'name' => 'The Burger Joint',
                'email' => 'info@burgerjoint.com',
                'phone' => '+1 (555) 555-0123',
                'address' => '789 Elm Street, Chicago, IL 60601',
                'is_active' => true,
                'is_verified' => true,
                'subscription_plan' => 'annual',
                'subscription_amount' => 299.99,
                'subscription_expires_at' => Carbon::now()->addYear(),
                'verified_at' => Carbon::now()->subDays(15),
            ]
        );

        // Create owner for The Burger Joint
        User::firstOrCreate(
            ['email' => 'info@burgerjoint.com'],
            [
                'name' => 'Bob Johnson',
                'password' => Hash::make('password'),
                'restaurant_id' => $restaurant3->id,
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        // 4. Suspended Restaurant
        $restaurant4 = Restaurant::firstOrCreate(
            ['slug' => 'taco-fiesta'],
            [
                'name' => 'Taco Fiesta',
                'email' => 'owner@tacofiesta.com',
                'phone' => '+1 (555) 246-8135',
                'address' => '321 Pine Street, Austin, TX 78701',
                'is_active' => false,
                'is_verified' => true,
                'subscription_plan' => 'monthly',
                'subscription_amount' => 29.99,
                'subscription_expires_at' => Carbon::now()->addDays(5), // Expiring soon
                'verified_at' => Carbon::now()->subDays(60),
                'suspended_at' => Carbon::now()->subDays(5),
            ]
        );

        // Create owner for Taco Fiesta
        User::firstOrCreate(
            ['email' => 'owner@tacofiesta.com'],
            [
                'name' => 'Carlos Rodriguez',
                'password' => Hash::make('password'),
                'restaurant_id' => $restaurant4->id,
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        // 5. Another Pending Restaurant
        $restaurant5 = Restaurant::firstOrCreate(
            ['slug' => 'le-petit-bistro'],
            [
                'name' => 'French Bistro Le Petit',
                'email' => 'chef@lepetit.com',
                'phone' => '+1 (555) 369-2580',
                'address' => '654 Maple Drive, San Francisco, CA 94102',
                'is_active' => false,
                'is_verified' => false,
                'subscription_plan' => 'semi_annual',
                'subscription_amount' => 149.99,
            ]
        );

        // Create owner for Le Petit Bistro
        User::firstOrCreate(
            ['email' => 'chef@lepetit.com'],
            [
                'name' => 'Pierre Dubois',
                'password' => Hash::make('password'),
                'restaurant_id' => $restaurant5->id,
                'role' => 'admin',
                'is_active' => true,
            ]
        );
    }
}
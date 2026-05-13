<?php

namespace Database\Factories;

use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Restaurant>
 */
class RestaurantFactory extends Factory
{
    protected $model = Restaurant::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->company . ' Restaurant';
        
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'logo' => null,
            'primary_color' => $this->faker->hexColor(),
            'secondary_color' => $this->faker->hexColor(),
            'description' => $this->faker->paragraph(),
            'is_active' => true,
            'is_verified' => true,
            'subscription_expires_at' => $this->faker->dateTimeBetween('now', '+1 year'),
            'subscription_plan' => $this->faker->randomElement(['basic', 'premium', 'enterprise']),
            'subscription_amount' => $this->faker->randomFloat(2, 29.99, 199.99),
            'verified_at' => now(),
            'rejected_at' => null,
            'suspended_at' => null,
            'rejection_reason' => null,
            'settings' => [],
        ];
    }

    /**
     * Indicate that the restaurant is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the restaurant is unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_verified' => false,
            'verified_at' => null,
        ]);
    }

    /**
     * Indicate that the restaurant is suspended.
     */
    public function suspended(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
            'suspended_at' => now(),
        ]);
    }

    /**
     * Indicate that the restaurant subscription has expired.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'subscription_expires_at' => $this->faker->dateTimeBetween('-1 year', '-1 day'),
        ]);
    }
}
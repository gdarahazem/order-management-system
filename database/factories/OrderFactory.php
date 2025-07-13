<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Create products if none exist
        if (Product::count() === 0) {
            Product::factory()->count(3)->create();
        }

        $productIds = Product::inRandomOrder()->limit(2)->pluck('id')->toArray();
        $totalPrice = Product::whereIn('id', $productIds)->sum('price');

        return [
            'products' => $productIds,
            'total_price' => $totalPrice,
        ];
    }
}

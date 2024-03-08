<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => fake()->randomNumber(7, true),
            'batch' => fake()->numberBetween(1, 10),
            'name' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'supplierId' => fake()->numberBetween(1,10),
            'categoryId' => fake()->numberBetween(1,10),
            'images' => json_encode(array(fake()->imageUrl(640, 480, 'animals', true))),
            'stock' => fake()->numberBetween(0, 100),
            'buyPrice' => fake()->randomNumber(5, true),
            'sellPrice' => fake()->randomNumber(5, true),
            'expiredDate' => fake()->dateTimeThisCentury('+2 years'),
            'userId' => fake()->numberBetween(1,3),
        ];
    }
}

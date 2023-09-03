<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MerchSale>
 */
class MerchSaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'item' => 'Product ' . $this->faker->randomNumber(1, true),
            'count' => $this->faker->randomDigit(),
            'price' => $this->faker->randomNumber(2, false),
            'created_at' => $this->faker->dateTimeBetween('-3 months', 'now'),
        ];
    }
}

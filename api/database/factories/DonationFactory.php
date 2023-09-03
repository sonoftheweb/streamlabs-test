<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Donation>
 */
class DonationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'amount' => rand(1000, 250000),
            'currency' => $this->faker->currencyCode,
            'donation_message' => $this->faker->sentence,
            'created_at' => $this->faker->dateTimeBetween('-3 months', 'now'),
        ];
    }
}

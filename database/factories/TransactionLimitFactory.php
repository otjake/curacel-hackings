<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TransactionLimit>
 */
class TransactionLimitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'transaction_type' => $this->faker->randomElement(['withdrawal', 'deposit']),
            'currency' => $this->faker->currencyCode(),
            'single_transaction_limit' => $this->faker->numberBetween(100, 10000),
            'daily_cumulative_limit' => $this->faker->numberBetween(10000, 50000),
            'creator_id' => \App\Models\Admin::factory(),
            'is_default' => $this->faker->boolean,
        ];
    }
}

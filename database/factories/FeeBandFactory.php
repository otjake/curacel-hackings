<?php

namespace Database\Factories;

use App\Enums\TransactionType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FeeBand>
 */
class FeeBandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->name,
            'is_percentage' => $this->faker->boolean,
            'fee' => $this->faker->randomFloat(2, 0, 100),
            'is_default' => $this->faker->boolean,
            'is_active' => true,
            'type' => TransactionType::PAYMENT,
        ];
    }
}

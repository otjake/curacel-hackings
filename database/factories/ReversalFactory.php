<?php

namespace Database\Factories;

use App\Models\Payer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reversal>
 */
class ReversalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reference' => fake()->uuid,
            'amount' => fake()->randomFloat(2, 100, 1000),
            'currency' => 'NGN',
            'payer_id' => Payer::factory(),
        ];
    }
}

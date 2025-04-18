<?php

namespace Database\Factories;

use App\Enums\Currency;
use App\Enums\DepositStatus;
use App\Models\Payer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Deposit>
 */
class DepositFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'amount' => $this->faker->randomFloat(2, 100, 1000),
            'status' => DepositStatus::PENDING,
            'currency' => Currency::NGN,
            'payer_id' => Payer::factory(),
            'reference' => $this->faker->uuid,
        ];
    }
}

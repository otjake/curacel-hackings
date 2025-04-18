<?php

namespace Database\Factories;

use App\Models\Bank;
use App\Models\Payer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PendingPayout>
 */
class PendingPayoutFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'payer_id' => Payer::factory(),
            'amount' => $this->faker->numberBetween(100, 10000),
            'currency' => $this->faker->currencyCode(),
            'initiator_id' => User::factory(),
            'account_number' => $this->faker->iban('NG'),
            'account_name' => $this->faker->name(),
            'is_bulk' => false,
            'expires_at' => now()->addMinutes(5),
            'bank_id' => Bank::factory(),
        ];
    }
}

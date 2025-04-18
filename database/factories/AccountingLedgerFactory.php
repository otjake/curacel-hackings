<?php

namespace Database\Factories;

use App\Enums\Currency;
use App\Enums\LedgerCode;
use App\Enums\LedgerType;
use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AccountingLedger>
 */
class AccountingLedgerFactory extends Factory
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
            'currency' => Currency::NGN->value,
            'opening_balance' => 0,
            'opened_at' => now(),
            'type' => $this->faker->randomElement(LedgerType::cases()),
            'code' => $this->faker->randomElement(LedgerCode::cases()),
            'creator_id' => Admin::factory(),
        ];
    }
}

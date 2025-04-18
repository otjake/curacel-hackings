<?php

namespace Database\Factories;

use App\Enums\Currency;
use App\Enums\PaymentCategory;
use App\Enums\PaymentStatus;
use App\Models\ApprovalLevel;
use App\Models\Beneficiary;
use App\Models\Payer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'amount' => $this->faker->randomFloat(2, 10000, 20000),
            'status' => PaymentStatus::PENDING,
            'currency' => Currency::NGN->value,
            'payer_id' => Payer::factory(),
            'reference' => $this->faker->uuid,
            'category' => PaymentCategory::OTHER,
            'beneficiary_id' => Beneficiary::factory(),
            'approval_level' => ApprovalLevel::factory(),
        ];
    }
}

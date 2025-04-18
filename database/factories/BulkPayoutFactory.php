<?php

namespace Database\Factories;

use App\Enums\ActionSource;
use App\Enums\Currency;
use App\Enums\PaymentStatus;
use App\Models\ApprovalLevel;
use App\Models\Payer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BulkPayout>
 */
class BulkPayoutFactory extends Factory
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
            'batch_name' => $this->faker->name,
            'status' => PaymentStatus::AWAITING_APPROVAL,
            'reference' => $this->faker->uuid,
            'approval_level' => ApprovalLevel::factory(),
            'source' => ActionSource::WEB_APP,
            'amount' => $this->faker->randomFloat(2, 10000, 20000),
            'fee' => $this->faker->randomFloat(2, 100, 200),
            'beneficiary_count' => $this->faker->numberBetween(1, 10),
            'currency' => Currency::NGN,
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Beneficiary>
 */
class BeneficiaryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'bank_code' => $this->faker->randomNumber(4),
            'payment_provider_recipient_id' => $this->faker->uuid,
            'bank_name' => $this->faker->word,
            'account_number' => $this->faker->bankAccountNumber,
        ];
    }
}

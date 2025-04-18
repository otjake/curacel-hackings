<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaymentCollection>
 */
class PaymentCollectionFactory extends Factory
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
            'currency' => 'NGN',
            'customer_id' => Customer::factory(),
            'description' => $this->faker->sentence,
            'reference' => $this->faker->uuid,
        ];
    }
}

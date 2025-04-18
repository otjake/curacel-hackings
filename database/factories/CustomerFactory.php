<?php

namespace Database\Factories;

use App\Models\Payer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
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
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'code' => $this->faker->uuid,
        ];
    }
}

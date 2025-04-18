<?php

namespace Database\Factories;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'payer_id' => \App\Models\Payer::factory(),
            'status' => \App\Enums\InvoiceStatus::PENDING,
            'is_draft' => false,
            'reference' => $this->faker->uuid,
            'sub_total' => $this->faker->randomFloat(2),
            'total' => $this->faker->randomFloat(2),
            'currency' => 'NGN',
            'title' => $this->faker->sentence,
            'due_date' => $this->faker->date,
        ];
    }
}

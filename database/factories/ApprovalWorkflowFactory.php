<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ApprovalWorkflow>
 */
class ApprovalWorkflowFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'payer_id' => \App\Models\Payer::factory(),
            'code' => $this->faker->unique()->word(),
            'name' => $this->faker->unique()->name(),
            'is_active' => true,
            'for_all_amounts' => true,
            'creator_id' => \App\Models\User::factory(),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Enums\RequestAction;
use App\Models\ApprovalWorkflow;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ApprovalLevel>
 */
class ApprovalLevelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'workflow_id' => ApprovalWorkflow::factory(),
            'level' => 1,
            'action' => RequestAction::PAYOUT_INITIATE,
        ];
    }
}

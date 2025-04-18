<?php

namespace Database\Factories;

use App\Enums\PaymentProvider;
use App\Enums\WebhookEvent;
use App\Models\Payer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WebhookLog>
 */
class WebhookLogFactory extends Factory
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
            'event' => WebhookEvent::DEPOSIT_SUCCESSFUL,
            'payload' => [$this->faker->text],
            'provider' => PaymentProvider::ANCHOR->value,
        ];
    }
}

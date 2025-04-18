<?php

namespace Database\Factories;

use App\Models\FeeBand;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FeeBandRange>
 */
class FeeBandRangeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fee_band_id' => FeeBand::factory(),
            'min_amount' => $this->faker->numberBetween(0, 100),
            'max_amount' => $this->faker->numberBetween(1000, 10000),
            'is_percentage' => $this->faker->boolean,
            'fee' => $this->faker->randomFloat(2, 0, 100),
            'is_active' => true,
        ];
    }
}

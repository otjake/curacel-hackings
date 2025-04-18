<?php

namespace Database\Factories;

use App\Models\Bank;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bank>
 */
class BankFactory extends Factory
{
    protected $model = Bank::class;

    public function definition()
    {
        return [
            'code' => $this->faker->unique()->numerify('###'),
            'name' => $this->faker->company,
            'country_id' => 1,
        ];
    }
}

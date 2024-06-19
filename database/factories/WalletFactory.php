<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Wallet>
 */
class WalletFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id'        => $this->faker->unique()->randomNumber(),
            'person_id' => $this->faker->unique()->randomNumber(),
            'balance'   => $this->faker->randomFloat(2, 0, 1000)
        ];
    }
}

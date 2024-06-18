<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Core\Enums\PersonDocumentType;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Person>
 */
class PersonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'            => $this->faker->name,
            'document_number' => $this->faker->numerify('##############'),
            'document_type'   => $this->faker->randomElement(PersonDocumentType::values()),
        ];
    }
}

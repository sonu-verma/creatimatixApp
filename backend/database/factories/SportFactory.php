<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sport>
 */
class SportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_turf' => $this->faker->numberBetween(1, 50),
            'name' => $this->faker->word,
            'id_sport' => $this->faker->numberBetween(1, 13),
            'rate_per_hour' => $this->faker->randomFloat(2, 100, 1000),
            'dimensions' => $this->faker->randomElement(['5x5', '7x7', '9x9']),
            'capacity' => $this->faker->numberBetween(1, 20),
            'rules' => $this->faker->paragraphs(3, true),
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}

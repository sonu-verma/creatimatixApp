<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Turf>
 */
class TurfFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $company_name = $this->faker->unique(true)->company;
        return [
            'name' => $company_name,
            'slug' => generateSlug($company_name),
            'location' => $this->faker->city,
            'address' => $this->faker->address,
            'timing' => $this->faker->time(),
            'description' => $this->faker->paragraph,
            'features' => $this->faker->paragraph(2),
            'benefits' => $this->faker->paragraph(nbSentences: 4),
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'status' => 1,
            'rules' => $this->faker->paragraph(nbSentences: 8),
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}

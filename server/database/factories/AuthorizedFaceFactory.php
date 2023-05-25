<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AuthorizedFace>
 */
class AuthorizedFaceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Creazione di un volto autorizzato con dati casuali
        return [
            'name' => $this->faker->name(),
            'encoding' => 0,
            'is_authorized' => $this->faker->boolean(),
            'img_path' => $this->faker->imageUrl(),
        ];
    }
}

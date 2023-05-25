<?php

namespace Database\Factories;

use App\Models\AuthorizedFace;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AccessAttempt>
 */
class AccessAttemptFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = AuthorizedFace::all()->random();
        // Creazione di un accesso per un utente casuale
        return [
            'attempted_at' => $this->faker->dateTimeBetween('-7 days'),
            'authorized_face_id' => $user->id,
            'authorized' => $user->is_authorized,
        ];
    }
}

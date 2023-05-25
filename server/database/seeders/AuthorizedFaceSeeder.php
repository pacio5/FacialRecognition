<?php

namespace Database\Seeders;

use App\Models\AuthorizedFace;
use Illuminate\Database\Seeder;

class AuthorizedFaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Creazione di 10 volti autorizzati con dati casuali
        AuthorizedFace::factory()->count(10)->create();
    }
}

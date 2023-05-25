<?php

namespace Database\Seeders;

use App\Models\AccessAttempt;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccessAttemptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Creazione di 30 accessi casuali tra gli utenti già presenti
        AccessAttempt::factory()->count(30)->create();
    }

}

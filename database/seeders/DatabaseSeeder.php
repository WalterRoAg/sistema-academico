<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Llama a los seeders que acabamos de crear
        $this->call([
            RolSeeder::class,
          //  PermisoSeeder::class,
            ProfesionSeeder::class,
            UsuarioSeeder::class,
            AcademicoSeeder::class,
        ]);
    }
}
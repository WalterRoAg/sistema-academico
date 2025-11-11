<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Rol; // <-- Importa el modelo

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Rol::firstOrCreate(['nombre' => 'administrador']);
        Rol::firstOrCreate(['nombre' => 'coordinador']);
        Rol::firstOrCreate(['nombre' => 'docente']);
        Rol::firstOrCreate(['nombre' => 'autoridad']);
    }
}
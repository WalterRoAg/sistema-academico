<?php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Profesion;

class ProfesionSeeder extends Seeder
{
    public function run(): void
    {
        $profesiones = [
            ['nombre' => 'Ingeniería de Sistemas'],
            ['nombre' => 'Ingeniería Informática'],
            ['nombre' => 'Licenciatura en Educación'],
            ['nombre' => 'Arquitectura'],
        ];

        foreach ($profesiones as $p) {
            Profesion::firstOrCreate($p);
        }
    }
}
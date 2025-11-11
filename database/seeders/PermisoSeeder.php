<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permiso; // <-- Importa el modelo

class PermisoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lista de todos nuestros permisos (Casos de Uso)
        $permisos = [
            'gestionar-usuarios',
            'gestionar-roles',
            'gestionar-docentes',
            'gestionar-materias',
            'gestionar-aulas',
            'gestionar-grupos',
            'asignar-horarios',
            'registrar-asistencia',
            'ver-reportes',
            'ver-bitacora',
            'importar-datos',
            'generar-cuentas',
            'ver-panel-qr',
        ];

        foreach ($permisos as $permiso) {
            // Crea el permiso solo si no existe
            Permiso::firstOrCreate(['nombre' => $permiso]);
        }
    }
}
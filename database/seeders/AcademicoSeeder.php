<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Aula;
use App\Models\Materia;
use App\Models\Grupo;
use App\Models\Horario;
use App\Models\PeriodoAcademico;
use App\Models\GrupoMateria;
use Carbon\Carbon; 
use Illuminate\Support\Facades\DB; // <-- Importante para el CHECK

class AcademicoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ... (Aulas, Materias, Grupos se crean igual) ...
        Aula::firstOrCreate(['numero' => 'A-101'], ['piso' => 1, 'capacidad' => 40, 'activo' => true]);
        Aula::firstOrCreate(['numero' => 'A-102'], ['piso' => 1, 'capacidad' => 40, 'activo' => true]);
        Aula::firstOrCreate(['numero' => 'L-201'], ['piso' => 2, 'capacidad' => 30, 'activo' => true]);
        Aula::firstOrCreate(['numero' => 'L-202'], ['piso' => 2, 'capacidad' => 30, 'activo' => false]);

        Materia::firstOrCreate(['sigla' => 'SIS101'], ['nombre' => 'Introducción a la Informática', 'nivel' => 'Primer Semestre']);
        Materia::firstOrCreate(['sigla' => 'SIS102'], ['nombre' => 'Algoritmos y Programación I', 'nivel' => 'Primer Semestre']);
        Materia::firstOrCreate(['sigla' => 'MAT101'], ['nombre' => 'Cálculo I', 'nivel' => 'Primer Semestre']);

        Grupo::firstOrCreate(['nombre' => 'SA']);
        Grupo::firstOrCreate(['nombre' => 'SB']);
        Grupo::firstOrCreate(['nombre' => 'SC']);

        // --- 4. Poblar Horarios (Bloques) --- ¡BLOQUE CORREGIDO! ---
        
        Horario::truncate(); 
        
        // --- ¡AQUÍ AÑADIMOS DOMINGO! ---
        $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        // --- FIN DEL CAMBIO ---

        // (Aprovechamos de añadir el CHECK constraint a la migración si se nos olvidó)
        if (DB::connection()->getDriverName() == 'pgsql') {
            DB::statement("ALTER TABLE horario DROP CONSTRAINT IF EXISTS horario_dia_check;");
            DB::statement("ALTER TABLE horario ADD CONSTRAINT horario_dia_check CHECK (dia IN ('Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'));");
        }

        $horaFinDia = Carbon::createFromTimeString('22:00:00');
        
        foreach ($dias as $dia) {
            $horaInicioBloque = Carbon::createFromTimeString('07:00:00');
            while (true) {
                $horaFinBloque = $horaInicioBloque->copy()->addMinutes(90);
                if ($horaFinBloque > $horaFinDia) {
                    break;
                }
                Horario::create([ 
                    'dia' => $dia,
                    'hora_ini' => $horaInicioBloque->format('H:i:s'),
                    'hora_fin' => $horaFinBloque->format('H:i:s')
                ]);
                $horaInicioBloque = $horaFinBloque;
            }
        }

        // --- 5. Poblar Periodo Académico ---
        PeriodoAcademico::firstOrCreate(
            ['nombre' => '2-2025'],
            [
                'fecha_inicio' => '2025-08-01',
                'fecha_fin' => '2025-12-31',
                'activo' => true
            ]
        );

        // --- 6. Abrir Materias para la Gestión Activa ---
        
        $periodoActivo = PeriodoAcademico::where('nombre', '2-2025')->first();
        $grupoSA = Grupo::where('nombre', 'SA')->first();
        $grupoSB = Grupo::where('nombre', 'SB')->first();
        $materiaSIS101 = Materia::where('sigla', 'SIS101')->first();
        $materiaSIS102 = Materia::where('sigla', 'SIS102')->first();
        $materiaMAT101 = Materia::where('sigla', 'MAT101')->first();

        if ($periodoActivo && $grupoSA && $grupoSB && $materiaSIS101 && $materiaSIS102 && $materiaMAT101) {
            GrupoMateria::firstOrCreate(
                ['grupo_id' => $grupoSA->id, 'materia_sigla' => $materiaSIS101->sigla, 'periodo_id' => $periodoActivo->id],
                ['activo' => true]
            );
            GrupoMateria::firstOrCreate(
                ['grupo_id' => $grupoSB->id, 'materia_sigla' => $materiaSIS101->sigla, 'periodo_id' => $periodoActivo->id],
                ['activo' => true]
            );
            GrupoMateria::firstOrCreate(
                ['grupo_id' => $grupoSA->id, 'materia_sigla' => $materiaSIS102->sigla, 'periodo_id' => $periodoActivo->id],
                ['activo' => true]
            );
            GrupoMateria::firstOrCreate(
                ['grupo_id' => $grupoSA->id, 'materia_sigla' => $materiaMAT101->sigla, 'periodo_id' => $periodoActivo->id],
                ['activo' => true]
            );
            GrupoMateria::firstOrCreate(
                ['grupo_id' => $grupoSB->id, 'materia_sigla' => $materiaMAT101->sigla, 'periodo_id' => $periodoActivo->id],
                ['activo' => true]
            );
        }
    }
}
<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Aula;
use App\Models\Horario;
use App\Models\HorarioClase;
use Illuminate\Support\Collection;

class DisponibilidadAulaController extends Controller
{
    /**
     * Muestra TODAS las aulas y su estado (Libre/Ocupado)
     * según el filtro de horario.
     * (CU-18 MEJORADO)
     */
    public function index(Request $request)
    {
        // 1. Obtener todos los horarios para el <select>
        
        // --- 👇 ¡AQUÍ ESTÁ LA CORRECCIÓN DEL ORDEN! 👇 ---
        // Forzamos el orden de los días de la semana con un CASE
        $horarios = Horario::orderByRaw("
                CASE
                    WHEN dia = 'Lunes' THEN 1
                    WHEN dia = 'Martes' THEN 2
                    WHEN dia = 'Miércoles' THEN 3
                    WHEN dia = 'Jueves' THEN 4
                    WHEN dia = 'Viernes' THEN 5
                    WHEN dia = 'Sábado' THEN 6
                    WHEN dia = 'Domingo' THEN 7
                    ELSE 8
                END
            ")
            ->orderBy('hora_ini') // Luego ordenamos por hora de inicio
            ->get();

        // 2. Obtener TODAS las aulas, sin filtrar
        $aulas = Aula::orderBy('numero')->get();

        // 3. Preparar la lista de aulas ocupadas
        $aulasOcupadasIds = collect(); 

        // 4. Si el usuario ha filtrado por un horario...
        if ($request->filled('horario_id')) {
            $aulasOcupadasIds = HorarioClase::where('horario_id', $request->horario_id)
                                          ->pluck('aula_id'); 
        }

        // 5. Devolvemos la vista
        return view('coordinador.aulas_disponibles.index', compact(
            'aulas', 
            'horarios',
            'aulasOcupadasIds' 
        ));
    }
}
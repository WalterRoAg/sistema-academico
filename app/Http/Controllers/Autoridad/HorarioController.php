<?php

namespace App\Http\Controllers\Autoridad;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HorarioClase; // El modelo principal
use App\Models\User;         // Para filtrar por Docente
use App\Models\Materia;      // Para filtrar por Materia

class HorarioController extends Controller
{
    /**
     * Muestra la consulta de horarios de docentes (CU-07).
     */
    public function index(Request $request)
    {
        // 1. Empezamos la consulta
        $query = HorarioClase::with([
            'horario', // Para ver Día y Hora
            'aula',    // Para ver el Aula
            'grupoMateria.materia', // Para ver la Materia
            'docente.persona'       // Para ver el nombre del Docente
        ]);

        // 2. Aplicar Filtros
        
        // Filtro: Docente
        if ($request->filled('docente_id')) {
            // El 'docente_id' es un 'user_id'
            $user = User::find($request->docente_id);
            if ($user && $user->persona_id) {
                // Filtramos por el 'persona_id' del docente
                $query->where('docente_persona_id', $user->persona_id);
            }
        }

        // Filtro: Materia
        if ($request->filled('materia_id')) {
            $query->whereHas('grupoMateria.materia', function($q) use ($request) {
                $q->where('id', $request->materia_id);
            });
        }

        // 3. Obtener resultados
        $horariosClase = $query->get()
                            // Agrupamos por docente para la vista
                            ->groupBy('docente_persona_id'); 

        // 4. Datos para los <select> de los filtros
        $docentes = User::whereHas('rol', function($q) {
                        $q->where('nombre', 'docente');
                    })->with('persona')->get()->sortBy('persona.nombre');
        
        $materias = Materia::orderBy('nombre')->get();

        return view('autoridad.horarios.index', compact(
            'horariosClase', // Datos agrupados
            'docentes',
            'materias'
        ));
    }
}
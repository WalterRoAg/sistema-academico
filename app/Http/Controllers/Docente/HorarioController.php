<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PeriodoAcademico;
use App\Models\HorarioClase;
use Illuminate\Support\Facades\Auth; // Importante para saber quién es el usuario

class HorarioController extends Controller
{
    /**
     * Muestra el horario asignado al docente autenticado.
     */
    public function index()
    {
        // 1. Obtener el usuario docente autenticado
        $user = Auth::user();
        
        // Asumiendo que el 'persona_id' en la tabla 'users' está correctamente enlazado
        $docentePersonaId = $user->persona_id; 

        // 2. Obtener la gestión activa
        $periodoActivo = PeriodoAcademico::where('activo', true)->first();

        if (!$periodoActivo) {
            return redirect()->route('docente.dashboard')
                             ->with('error', 'No hay un periodo académico activo en este momento.');
        }

        // 3. Obtener los slots asignados SOLAMENTE a este docente
        $slotsAsignados = HorarioClase::where('periodo_id', $periodoActivo->id)
            ->where('docente_persona_id', $docentePersonaId) // <-- El filtro clave
            ->with([
                'horario',
                'aula',
                'grupoMateria.grupo',
                'grupoMateria.materia'
            ])
            ->get();

        // 4. Definir la estructura del calendario (igual que el coordinador)
        $dias_semana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        $horas_del_dia = [
            '07:00', '08:30', '10:00', '11:30', '13:00', '14:30',
            '16:00', '17:30', '19:00', '20:30'
        ];

        // 5. Devolver la vista del docente con su horario
        return view('docente.horario.index', [
            'periodo' => $periodoActivo,
            'slotsAsignados' => $slotsAsignados,
            'dias_semana' => $dias_semana,
            'horas_del_dia' => $horas_del_dia,
        ]);
    }
}
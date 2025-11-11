<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HorarioClase;
use App\Models\Horario;
use App\Models\Aula;
use App\Models\GrupoMateria;
use App\Models\Grupo;
use App\Models\Docente; // ¡CORRECTO!
use App\Models\Materia;
use App\Models\PeriodoAcademico;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class HorarioController extends Controller
{
    /**
     * Muestra la vista de planificación semanal con filtros (CU-07).
     */
    public function index(Request $request)
    {
        // 1. Obtener el período académico activo (Asumo que esta lógica existe)
        $periodo = PeriodoAcademico::where('activo', true)->firstOrFail();
        
        // 2. Obtener datos base para los formularios
        $horariosBase = Horario::orderByRaw("
            CASE
                WHEN dia = 'Lunes' THEN 1
                WHEN dia = 'Martes' THEN 2
                WHEN dia = 'Miércoles' THEN 3
                WHEN dia = 'Jueves' THEN 4
                WHEN dia = 'Viernes' THEN 5
                WHEN dia = 'Sábado' THEN 6
                ELSE 7
            END
        ")->orderBy('hora_ini')->get();
        
        $aulas = Aula::orderBy('numero')->get();
        $grupos = Grupo::orderBy('nombre')->get();
        $materias = Materia::orderBy('nombre')->get();
        
        $docentes = Docente::with('persona')->get(); 

        // 3. Preparar la consulta principal (Horarios Asignados)
        $query = HorarioClase::with([
            'horario', 'aula', 'docente.persona', 
            'grupoMateria.materia', 'grupoMateria.grupo'
        ])->where('periodo_id', $periodo->id);

        // 4. Aplicar Filtros (Vista Semanal)
        $filtroDocente = $request->input('docente_id');
        $filtroGrupo = $request->input('grupo_id');

        if ($filtroDocente) {
             $query->where('docente_persona_id', $filtroDocente);
        }

        if ($filtroGrupo) {
             $query->whereHas('grupoMateria.grupo', function($q) use ($filtroGrupo) {
                 $q->where('id', $filtroGrupo);
             });
        }
        
        $horariosAsignados = $query->get();

        // 5. Crear la estructura para la cuadrícula semanal
        $dias_semana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        $horas_del_dia = $horariosBase->pluck('hora_ini')->map(fn($h) => $h->format('H:i'))->unique()->sort();
        
        // Mapeamos los slots asignados para la vista
        $slotsAsignados = $horariosAsignados->map(function ($item) {
             $item->horario->hora_ini = $item->horario->hora_ini;
             return $item;
        });


        return view('coordinador.planificacion.index', compact(
            'periodo', 'horariosBase', 'aulas', 'grupos', 'materias', 'docentes', 
            'horariosAsignados', 'dias_semana', 'horas_del_dia', 'slotsAsignados', 
            'filtroDocente', 'filtroGrupo'
        ));
    }

    /**
     * Almacena una nueva asignación de clase (CU-08).
     */
    public function store(Request $request)
    {
        // 1. Encontrar el GrupoMateria_id
        $grupoMateria = GrupoMateria::where('grupo_id', $request->grupo_id)
            ->where('materia_sigla', $request->materia_sigla)
            ->where('periodo_id', $request->periodo_id)
            ->first();

        if (!$grupoMateria) {
            return redirect()->back()->with('error', 'La combinación de Grupo y Materia no existe o no ha sido aperturada para este período.');
        }

        // 2. Validar los datos
        $datosValidados = $request->validate([
            'horario_id' => ['required', 'integer', 'exists:horario,id'],
            // --- 👇 CORRECCIÓN CLAVE: CAMBIAR 'aulas' a 'aula' 👇 ---
            'aula_id' => ['required', 'integer', 'exists:aula,id'], 
            'docente_persona_id' => ['required', 'integer', 'exists:docente,persona_id'],
        ]);

        // 3. Verificaciones de Disponibilidad (Regla de Negocio)
        
        // 3a. Verificar si el Aula ya está ocupada en ese horario
        $aulaOcupada = HorarioClase::where('horario_id', $datosValidados['horario_id'])
            ->where('aula_id', $datosValidados['aula_id'])
            ->exists();

        if ($aulaOcupada) {
            return redirect()->back()->with('error', 'El Aula ya está ocupada en ese bloque horario.');
        }

        // 3b. Verificar si el Docente ya está asignado en ese horario
        $docenteOcupado = HorarioClase::where('horario_id', $datosValidados['horario_id'])
            ->where('docente_persona_id', $datosValidados['docente_persona_id'])
            ->exists();

        if ($docenteOcupado) {
            return redirect()->back()->with('error', 'El Docente ya está asignado a otra clase en ese bloque horario.');
        }

        // 4. Crear el HorarioClase
        HorarioClase::create(array_merge($datosValidados, [
            'grupo_materia_id' => $grupoMateria->id,
            'periodo_id' => $request->periodo_id,
        ]));

        return redirect()->back()->with('status', 'Clase asignada y planificada correctamente.');
    }
    

    /**
     * Elimina una asignación de horario (CU-07 - DELETE).
     *
     * @param HorarioClase $horarioClase El registro inyectado por Route Model Binding.
     */
    public function destroy(HorarioClase $horarioClase)
    {
        try {
            // 1. Eliminamos el registro de la asignación.
            $horarioClase->delete();

            // 2. Redirigir de vuelta a la página anterior con un mensaje de éxito.
            return redirect()->back()->with('status', 'Horario de clase eliminado exitosamente.');

        } catch (\Exception $e) {
            // 3. Manejar cualquier error de la base de datos
            return redirect()->back()->with('error', 'Error al eliminar el horario: ' . $e->getMessage());
        }
    }
}
<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asistencia;
use App\Models\Docente;
use Carbon\Carbon;

class ReporteController extends Controller
{
    /**
     * Muestra el reporte de asistencia (CU-10)
     */
    public function reporteAsistencia(Request $request)
    {
        // 1. Validar filtros (opcionales)
        $request->validate([
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'docente_id' => 'nullable|integer|exists:docente,persona_id',
        ]);

        // 2. Obtener filtros
        // Si no hay fecha de inicio, por defecto es hoy
        $fechaInicio = $request->input('fecha_inicio', Carbon::today()->format('Y-m-d'));
        // Si no hay fecha de fin, por defecto es hoy
        $fechaFin = $request->input('fecha_fin', Carbon::today()->format('Y-m-d'));
        
        $docenteFiltro = $request->input('docente_id');

        // 3. Obtener la lista de docentes para el dropdown del filtro
        $docentes = Docente::with('persona')->where('activo', true)->get()->sortBy('persona.nombre');

        // 4. Construir la consulta del reporte
        $queryAsistencias = Asistencia::with([
            'horarioClase.horario', // Para el Día y Hora
            'horarioClase.aula', // Para el Aula
            'horarioClase.docente.persona', // Para el Nombre del Docente
            'horarioClase.grupoMateria.materia', // Para el Nombre de la Materia
            'horarioClase.grupoMateria.grupo', // Para el Grupo
        ])
        ->whereBetween('fecha_hora', [
            Carbon::parse($fechaInicio)->startOfDay(), // Desde las 00:00:00
            Carbon::parse($fechaFin)->endOfDay() // Hasta las 23:59:59
        ]);

        // 5. Aplicar filtro de docente si existe
        if ($docenteFiltro) {
            // whereHas para filtrar por la relación
            $queryAsistencias->whereHas('horarioClase', function ($q) use ($docenteFiltro) {
                $q->where('docente_persona_id', $docenteFiltro);
            });
        }

        // 6. Obtener resultados
        $asistencias = $queryAsistencias->orderBy('fecha_hora', 'desc')->get();

        // 7. Devolver la vista con los datos
        return view('coordinador.reportes.asistencia', [
            'asistencias' => $asistencias,
            'docentes' => $docentes,
            'fechaInicio' => $fechaInicio,
            'fechaFin' => $fechaFin,
            'docenteFiltro' => $docenteFiltro,
        ]);
    }
}
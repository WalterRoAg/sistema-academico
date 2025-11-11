<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asistencia;
use App\Models\User;
use App\Models\Materia;
use App\Exports\AsistenciasExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
class ReporteAsistenciaController extends Controller
{
    /**
     * Muestra la vista con los filtros para generar el reporte.
     */
   public function index()
    {
        // 1. La lógica para obtener los filtros es la misma
        $docentes = \App\Models\User::whereHas('rol', function($q) {
                        $q->where('nombre', 'docente');
                     })->with('persona')->get()->sortBy('persona.nombre');
        
        $materias = \App\Models\Materia::orderBy('nombre')->get();

        // 2. --- ¡AQUÍ ESTÁ LA SOLUCIÓN! ---
        // Detectamos el rol del usuario logueado
        $rol = strtolower(Auth::user()->rol->nombre);

        if ($rol === 'autoridad') {
            // Si es Autoridad, carga la vista de Autoridad
            return view('autoridad.reportes.index', compact('docentes', 'materias'));
        }
        
        // Si es cualquier otro (Admin), carga la vista de Admin
        return view('admin.reportes.asistencia.index', compact('docentes', 'materias'));
    }
    /**
     * Genera y descarga el reporte (PDF o Excel).
     */
   /**
     * Genera y descarga el reporte (PDF o Excel).
     */
    public function generar(Request $request)
    {
        // 1. Validar los datos (Sin cambios)
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'required|date|after_or_equal:fecha_inicio',
            'docente_id'   => 'nullable|integer|exists:users,id',
            'materia_id'   => 'nullable|integer|exists:materias,id',
            'formato'      => 'required|in:excel,pdf'
        ]);

        // 2. Encontrar el persona_id (Sin cambios)
        $docente_persona_id = null;
        if ($request->docente_id) {
            $user = User::find($request->docente_id);
            if ($user) {
                $docente_persona_id = $user->persona_id; 
            }
        }

     

        // 3. Ajustar las fechas para la consulta

        $fecha_inicio_ajustada = Carbon::parse($request->fecha_inicio)->startOfDay();
  
        $fecha_fin_ajustada = Carbon::parse($request->fecha_fin)->endOfDay();

        // -----------------------------------------------


        // 4. Construir la consulta
        $query = Asistencia::
                    with([
                        'horarioClase.docentePersona.user', 
                        'horarioClase.grupoMateria.materia', 
                        'horarioClase.aula'
                    ])
                 
                    ->whereBetween('fecha_hora', [$fecha_inicio_ajustada, $fecha_fin_ajustada])
                    // -----------------------------------------------
                    ->when($docente_persona_id, function ($q) use ($docente_persona_id) {
                        return $q->whereHas('horarioClase', function ($queryHorario) use ($docente_persona_id) {
                            $queryHorario->where('docente_persona_id', $docente_persona_id);
                        });
                    })
                    ->when($request->materia_id, function ($q) use ($request) {
                        return $q->whereHas('horarioClase.grupoMateria.materia', function ($queryMateria) use ($request) {
                            $queryMateria->where('id', $request->materia_id);
                        });
                    })
                    ->orderBy('fecha_hora', 'desc');

        // 5. Obtener los resultados
        $asistencias = $query->get();

        // 6. Preparar datos para las vistas (Sin cambios)
        $datos = [
            'asistencias'  => $asistencias,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin'    => $request->fecha_fin,
            'docente'      => $request->docente_id ? User::find($request->docente_id) : null,
            'materia'      => $request->materia_id ? Materia::find($request->materia_id) : null,
        ];
        
        // 7. Lógica de nombres de archivo (Tu código ya la tenía)
        if ($request->formato == 'pdf') {
            $extension = 'pdf';
        } else {
            $extension = 'xlsx'; 
        }
        
        $nombreArchivo = 'reporte_asistencia_' . now()->format('Y-m-d') . '.' . $extension;

        // 8. Generar y descargar el archivo (Sin cambios)
        if ($request->formato == 'pdf') {
            $pdf = Pdf::loadView('admin.reportes.asistencia.pdf', $datos);
            return $pdf->download($nombreArchivo);
        } else {
            return Excel::download(new AsistenciasExport($datos), $nombreArchivo);
        }
    }
}
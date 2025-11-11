<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GrupoMateria;
use App\Models\Materia;
use App\Models\Grupo;
use App\Models\HorarioClase;
use App\Models\PeriodoAcademico;
use Illuminate\Validation\Rule;

class GrupoMateriaController extends Controller
{
    /**
     * Muestra el formulario para "abrir" materias y la lista de materias ya abiertas.
     */
    public function index()
    {
        $periodoActivo = PeriodoAcademico::where('activo', true)->first();

        if (!$periodoActivo) {
            return redirect()->route('coordinador.dashboard')
                             ->with('error', 'Debe activar un Periodo Académico antes de gestionar la apertura de materias.');
        }

        // Datos para los dropdowns del formulario
        $materias = Materia::orderBy('nombre')->get();
        $grupos = Grupo::orderBy('nombre')->get();

        // Datos para la tabla (lo que ya está abierto en esta gestión)
        $materiasAbiertas = GrupoMateria::with(['materia', 'grupo'])
                                        ->where('periodo_id', $periodoActivo->id)
                                        ->get()
                                        ->sortBy('materia.nombre');

        return view('coordinador.grupo_materia.index', [
            'periodo' => $periodoActivo,
            'materias' => $materias,
            'grupos' => $grupos,
            'materiasAbiertas' => $materiasAbiertas,
        ]);
    }

    /**
     * Guarda el nuevo enlace (Abre la materia/grupo para la gestión).
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'materia_sigla' => ['required', 'exists:materia,sigla'],
            'grupo_id'      => ['required', 'exists:grupo,id'],
            'periodo_id'    => ['required', 'exists:periodo_academico,id'],
        ]);

        // Validar duplicados (Regla de negocio)
        $yaExiste = GrupoMateria::where('materia_sigla', $data['materia_sigla'])
                                  ->where('grupo_id', $data['grupo_id'])
                                  ->where('periodo_id', $data['periodo_id'])
                                  ->exists();
        
        if ($yaExiste) {
            return back()->with('error', 'Esta combinación de Materia y Grupo ya está abierta para esta gestión.');
        }

        // Crear el enlace
        GrupoMateria::create([
            'materia_sigla' => $data['materia_sigla'],
            'grupo_id' => $data['grupo_id'],
            'periodo_id' => $data['periodo_id'],
            'activo' => true
        ]);

        return redirect()->route('coordinador.grupo-materia.index')
                         ->with('status', 'Combinación Materia/Grupo abierta exitosamente.');
    }

    /**
     * Elimina el enlace (Cierra la materia/grupo).
     */
    public function destroy(GrupoMateria $grupoMateria)
    {
        // Validar si esta apertura ya tiene clases asignadas
        $tieneClases = HorarioClase::where('grupo_materia_id', $grupoMateria->id)->exists();

        if ($tieneClases) {
            return redirect()->route('coordinador.grupo-materia.index')
                             ->with('error', 'No se puede cerrar esta combinación porque ya tiene horarios asignados. Primero debe eliminar las clases asociadas.');
        }

        // Si no tiene clases, la borramos
        try {
            $grupoMateria->delete();
            return redirect()->route('coordinador.grupo-materia.index')
                             ->with('status', 'Combinación Materia/Grupo cerrada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('coordinador.grupo-materia.index')
                             ->with('error', 'Ocurrió un error inesperado al intentar cerrar la combinación.');
        }
    }
}
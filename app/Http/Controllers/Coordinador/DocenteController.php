<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Docente;
use App\Models\Persona; // <-- NUEVO
use App\Models\Profesion; // <-- NUEVO
use App\Models\ProfesionPersona; // <-- NUEVO
use Illuminate\Support\Facades\DB; // <-- NUEVO (Para transacciones)
use Illuminate\Validation\Rule;

class DocenteController extends Controller
{
    /**
     * Muestra la lista de docentes (CU-003)
     */
    public function index()
    {
        // 1. Buscamos todos los docentes.
        // Usamos 'with' para cargar la relación 'persona' que definimos en el Modelo Docente.
        $docentes = Docente::with('persona')
        ->where('activo', true)
        ->get();

        // 2. Devolvemos la vista
        return view('coordinador.docentes.index', [
            'docentes' => $docentes
        ]);
    }
    /**
 * Muestra el formulario para crear un nuevo docente (requiere profesiones).
 */
public function create()
{
    $profesiones = Profesion::orderBy('nombre')->get();
    return view('coordinador.docentes.create', ['profesiones' => $profesiones]);
}

/**
 * Guarda la nueva persona, el perfil de docente, y su profesión.
 */
public function store(Request $request)
{
    // 1. Validar los datos
    $request->validate([
        'carnet' => 'required|string|max:50|unique:persona,carnet',
        'nombre' => 'required|string|max:100',
        'telefono' => 'nullable|string|max:20',
        'profesion_id' => 'required|exists:profesion,id',
        'nivel_profesional' => 'required|string|max:50',
        'anos_experiencia' => 'nullable|integer|min:0',
        'fecha_ingreso' => 'required|date',
    ]);

    // 2. Usar una transacción para asegurar que las 3 tablas se guarden juntas
    DB::transaction(function () use ($request) {

        // 2a. Crear la Persona
        $persona = Persona::create([
            'carnet' => $request->carnet,
            'nombre' => $request->nombre,
            'telefono' => $request->telefono,
        ]);

        // 2b. Crear el perfil de Docente (Herencia 1-a-1)
        Docente::create([
            'persona_id' => $persona->id,
            'anos_experiencia' => $request->anos_experiencia,
            'fecha_ingreso' => $request->fecha_ingreso,
            'activo' => true,
        ]);

        // 2c. Asignar la Profesión (Tabla Pivote N-a-N)
        $persona->profesiones()->attach($request->profesion_id, [
            'nivel' => $request->nivel_profesional
        ]);
    });

    return redirect()->route('coordinador.docentes.index')
                     ->with('status', 'Docente registrado y perfil creado exitosamente.');
}
/**
 * Muestra el formulario para editar un docente.
 */
public function edit(Docente $docente)
{
    $profesiones = Profesion::orderBy('nombre')->get();

    // Obtenemos la profesión principal (y su nivel) para preseleccionar los campos
    $profesionActual = $docente->persona->profesiones()->first();

    return view('coordinador.docentes.edit', [
        'docente' => $docente,
        'profesiones' => $profesiones,
        'profesionActual' => $profesionActual
    ]);
}

/**
 * Actualiza el docente en la base de datos (Toca 3 Tablas).
 */
public function update(Request $request, Docente $docente)
{
    // 1. Validar los datos (ignorando el carnet actual)
    $request->validate([
        'carnet' => ['required', 'string', 'max:50', Rule::unique('persona', 'carnet')->ignore($docente->persona_id)],
        'nombre' => 'required|string|max:100',
        'telefono' => 'nullable|string|max:20',
        'profesion_id' => 'required|exists:profesion,id',
        'nivel_profesional' => 'required|string|max:50',
        'anos_experiencia' => 'nullable|integer|min:0',
        'fecha_ingreso' => 'required|date',
        'activo' => 'required|boolean', // Para activar/desactivar al docente
    ]);

    // 2. Usar una transacción para asegurar la integridad de las 3 tablas
    DB::transaction(function () use ($request, $docente) {

        // 2a. Actualizar la tabla PERSONA
        $docente->persona->update([
            'carnet' => $request->carnet,
            'nombre' => $request->nombre,
            'telefono' => $request->telefono,
        ]);

        // 2b. Actualizar la tabla DOCENTE
        $docente->update([
            'anos_experiencia' => $request->anos_experiencia,
            'fecha_ingreso' => $request->fecha_ingreso,
            'activo' => $request->activo,
        ]);

        // 3. Actualizar la tabla PIVOTE (ProfesionPersona)
        // Sincronizamos la nueva profesión, actualizando el campo 'nivel'
        $docente->persona->profesiones()->syncWithPivotValues(
            [$request->profesion_id], 
            ['nivel' => $request->nivel_profesional]
        );
    });

    return redirect()->route('coordinador.docentes.index')->with('status', 'Docente actualizado exitosamente.');
}
}
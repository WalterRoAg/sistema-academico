<?php
namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Aula; // <-- Importamos el Modelo Aula

class AulaController extends Controller
{
    /**
     * Muestra la lista de aulas (CU-005)
     */
    public function index()
    {
        // 1. Busca todas las aulas en la BD, ordenadas por número
        $aulas = Aula::orderBy('piso')->orderBy('numero')->get();

        // 2. Devuelve la vista y le pasa los datos
        return view('coordinador.aulas.index', [
            'aulas' => $aulas
        ]);
    }
    /**
 * Muestra el formulario para crear una nueva aula.
 */
public function create()
{
    // Solo muestra la vista del formulario
    return view('coordinador.aulas.create');
}

/**
 * Guarda la nueva aula en la base de datos.
 */
public function store(Request $request)
{
    // 1. Validar los datos del formulario
    $datosValidados = $request->validate([
        'numero' => 'required|string|max:20|unique:aula,numero', // Debe ser único en la tabla 'aula'
        'piso' => 'required|integer|min:0',
        'capacidad' => 'required|integer|min:1',
        'activo' => 'required|boolean',
    ]);

    // 2. Crear la nueva aula con los datos validados
    Aula::create($datosValidados);

    // 3. Redirigir de vuelta a la lista de aulas con un mensaje de éxito
    return redirect()->route('coordinador.aulas.index')
                     ->with('status', 'Aula registrada exitosamente.');
}

    /**
 * Muestra el formulario para editar un aula existente.
 * Laravel encontrará el $aula automáticamente gracias al {aula} en la URL.
 */
public function edit(Aula $aula)
{
    return view('coordinador.aulas.edit', [
        'aula' => $aula
    ]);
}

/**
 * Actualiza el aula en la base de datos.
 */
public function update(Request $request, Aula $aula)
{
    // 1. Validar los datos
    $datosValidados = $request->validate([
        // 'numero' debe ser único, PERO ignorando el aula actual
        'numero' => [
            'required',
            'string',
            'max:20',
            \Illuminate\Validation\Rule::unique('aula', 'numero')->ignore($aula->id),
        ],
        'piso' => 'required|integer|min:0',
        'capacidad' => 'required|integer|min:1',
        'activo' => 'required|boolean',
    ]);

    // 2. Actualizar el aula con los datos validados
    $aula->update($datosValidados);

    // 3. Redirigir de vuelta a la lista con un mensaje de éxito
    return redirect()->route('coordinador.aulas.index')
                     ->with('status', 'Aula actualizada exitosamente.');
}
}
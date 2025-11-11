<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Materia; // <-- Importamos el Modelo
use Illuminate\Validation\Rule; // <-- Importamos Rule para validación

class MateriaController extends Controller
{
    /**
     * Muestra la lista de materias (Listar)
     */
    public function index()
    {
        $materias = Materia::orderBy('nivel')->orderBy('nombre')->get();
        return view('coordinador.materias.index', ['materias' => $materias]);
    }

    /**
     * Muestra el formulario para crear una nueva materia (Crear - Vista)
     */
    public function create()
    {
        return view('coordinador.materias.create');
    }

    /**
     * Guarda la nueva materia en la base de datos (Crear - Lógica)
     */
    public function store(Request $request)
    {
        $datosValidados = $request->validate([
            'sigla' => 'required|string|max:20|unique:materia,sigla',
            'nombre' => 'required|string|max:100',
            'nivel' => 'nullable|string|max:50',
        ]);

        Materia::create($datosValidados);

        return redirect()->route('coordinador.materias.index')
                         ->with('status', 'Materia registrada exitosamente.');
    }

    /**
     * Muestra el formulario para editar una materia (Modificar - Vista)
     * Nota: Laravel usa 'Route Model Binding' para encontrar la materia por su PK (sigla).
     */
    public function edit(Materia $materia)
    {
        return view('coordinador.materias.edit', ['materia' => $materia]);
    }

    /**
     * Actualiza la materia en la base de datos (Modificar - Lógica)
     */
    public function update(Request $request, Materia $materia)
    {
        $datosValidados = $request->validate([
            'sigla' => [
                'required',
                'string',
                'max:20',
                Rule::unique('materia', 'sigla')->ignore($materia->sigla, 'sigla'),
            ],
            'nombre' => 'required|string|max:100',
            'nivel' => 'nullable|string|max:50',
        ]);

        $materia->update($datosValidados);

        return redirect()->route('coordinador.materias.index')
                         ->with('status', 'Materia actualizada exitosamente.');
    }
}
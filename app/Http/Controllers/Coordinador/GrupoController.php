<?php
namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Grupo;
use Illuminate\Validation\Rule;

class GrupoController extends Controller
{
    public function index()
    {
        $grupos = Grupo::orderBy('nombre')->get();
        return view('coordinador.grupos.index', ['grupos' => $grupos]);
    }

    public function create()
    {
        return view('coordinador.grupos.create');
    }

    public function store(Request $request)
    {
        $datosValidados = $request->validate([
            'nombre' => 'required|string|max:100|unique:grupo,nombre',
        ]);
        Grupo::create($datosValidados);
        return redirect()->route('coordinador.grupos.index')
                         ->with('status', 'Grupo registrado exitosamente.');
    }

    public function edit(Grupo $grupo)
    {
        return view('coordinador.grupos.edit', ['grupo' => $grupo]);
    }

    public function update(Request $request, Grupo $grupo)
    {
        $datosValidados = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:100',
                Rule::unique('grupo', 'nombre')->ignore($grupo->id),
            ],
        ]);
        $grupo->update($datosValidados);
        return redirect()->route('coordinador.grupos.index')
                         ->with('status', 'Grupo actualizado exitosamente.');
    }
}
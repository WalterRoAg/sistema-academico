<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rol;
use Illuminate\Http\Request;

class RolController extends Controller
{
    public function index()
    {
        $roles = Rol::orderBy('id')->get();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('admin.roles.create');
    }

    /**
     * --- ¡MÉTODO 'STORE' CORREGIDO! ---
     * (Para cuando crees nuevos roles)
     */
    public function store(Request $request)
    {
        $datosValidados = $request->validate([
            'nombre' => 'required|string|max:100|unique:rol,nombre',
            'caracteristica' => 'nullable|string|max:255' 
        ]);

        // Asignación Manual (Bypass $fillable)
        $rol = new Rol();
        $rol->nombre = $datosValidados['nombre'];
        $rol->caracteristica = $datosValidados['caracteristica'];
        $rol->save();

        return redirect()->route('admin.roles.index')->with('status', 'Rol creado exitosamente.');
    }

    /**
     * (Este método estaba bien)
     */
    public function edit(Rol $role)
    {
        return view('admin.roles.edit', ['rol' => $role]);
    }

    /**
     * --- ¡MÉTODO 'UPDATE' CORREGIDO! ---
     * Esta es la solución a tu problema
     */
    public function update(Request $request, Rol $role)
    {
        $datosValidados = $request->validate([
            'nombre' => 'required|string|max:100|unique:rol,nombre,' . $role->id, 
            'caracteristica' => 'nullable|string|max:255'
        ]);

        // Asignación Manual (Bypass $fillable)
        // 1. Asignamos los valores uno por uno
        $role->nombre = $datosValidados['nombre'];
        $role->caracteristica = $datosValidados['caracteristica'];
        
        // 2. Guardamos los cambios en la base de datos
        $role->save(); 

        return redirect()->route('admin.roles.index')->with('status', 'Rol actualizado exitosamente.');
    }
}
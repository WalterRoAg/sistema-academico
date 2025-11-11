<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    /**
     * Muestra la lista de usuarios (CU-013)
     */
    public function index()
    {
        // (Este método estaba perfecto, no se toca)
        $usuarios = User::with(['rol', 'persona'])
                            ->orderBy('nombre')
                            ->get();

        return view('admin.usuarios.index', [
            'usuarios' => $usuarios
        ]);
    }
    
    /**
    * Muestra el formulario para editar un usuario.
    */
    // 2. CORRECCIÓN: Cambiar (User $user) por (User $usuario)
    public function edit(User $usuario)
    {
        // 1. Cargamos todos los roles para poder mostrarlos en un <select>
        $roles = Rol::orderBy('nombre')->get();

        // 2. Devolvemos la vista 'edit', pasando el usuario
        //    que queremos editar ($usuario) y la lista de todos los roles ($roles).
        //    Laravel ahora SÍ encontrará el $usuario automáticamente.
        return view('admin.usuarios.edit', [
            'usuario' => $usuario, // 3. CORRECCIÓN: ($user ahora es $usuario)
            'roles' => $roles
        ]);
        
        /* Alternativa más limpia para el return:
         * return view('admin.usuarios.edit', compact('usuario', 'roles'));
         */
    }

   public function store(Request $request)
    {
        $datosValidados = $request->validate([
            'nombre' => ['required', 'string', 'unique:users,nombre'],
            'correo' => ['required', 'email', 'unique:users,correo'],
            'rol_id' => ['required', 'integer', 'exists:rol,id'],
            'activo' => ['required', 'boolean'],
            'persona_id' => ['required', 'integer', 'exists:persona,id', 'unique:users,persona_id'],
            'password' => ['required', 'string', 'min:8'],
            'caracteristica' => ['nullable', 'string', 'max:255'] // <-- REGLA AÑADIDA
        ]);
        
        // (Asumo que ya tienes el Mutator de 'password' en tu modelo User)
        User::create($datosValidados); 

        return redirect()->route('admin.usuarios.index')->with('status', 'Usuario creado exitosamente.');
    }


    public function update(Request $request, User $usuario)
    {
        // 1. Validar los datos que vienen del formulario
        $datosValidados = $request->validate([
            'nombre' => [
                'required', 'string',
                Rule::unique('users', 'nombre')->ignore($usuario->id),
            ],
            'correo' => [
                'required', 'email',
                Rule::unique('users', 'correo')->ignore($usuario->id),
            ],
            'rol_id' => ['required', 'integer', 'exists:rol,id'],
            'activo' => ['required', 'boolean'],
            'caracteristica' => ['nullable', 'string', 'max:255'] // <-- REGLA AÑADIDA
        ]);

        // 2. Actualizar el modelo 'User'
        $usuario->update($datosValidados);

        return redirect()->route('admin.usuarios.index')
                         ->with('status', 'Usuario actualizado exitosamente.');
    }

}
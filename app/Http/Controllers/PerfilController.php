<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password; // Para reglas de contraseña
use Spatie\Activitylog\Facades\Activity; // Para la bitácora

class PerfilController extends Controller
{
    /**
     * Muestra el formulario para cambiar la contraseña.
     * (CU-17 - Vista)
     */
    public function editPassword()
    {
        return view('perfil.password-edit');
    }

    /**
     * Actualiza la contraseña del usuario.
     * (CU-17 - Lógica)
     */
    public function updatePassword(Request $request)
    {
        // 1. Validar los datos del formulario
        $request->validate([
            // 'current_password' usa el 'getAuthPassword()' 
            // que definimos en tu modelo User (contrasena)
            'password_actual' => ['required', 'current_password'], 
            
            'nueva_password' => [
                'required', 
                'min:8', // Mínimo 8 caracteres
                'confirmed', // Debe coincidir con 'nueva_password_confirmation'
                'different:password_actual' // No puede ser igual a la anterior
            ],
        ], [
            // Mensajes en español
            'password_actual.current_password' => 'La contraseña actual no es correcta.',
            'nueva_password.confirmed' => 'La confirmación de la nueva contraseña no coincide.',
            'nueva_password.different' => 'La nueva contraseña no puede ser igual a la actual.',
            'nueva_password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
        ]);

        // 2. Actualizar la contraseña
        // Gracias al mutator 'setPasswordAttribute' en tu modelo User,
        // esto automáticamente hashea y guarda en la columna 'contrasena'.
        $request->user()->update([
            'password' => $request->nueva_password
        ]);

        // 3. Registrar en la bitácora (¡Buena práctica!)
        Activity::causedBy($request->user())
                ->log('Cambio de Contraseña Propia');

        // 4. Redirigir de vuelta con mensaje de éxito
        return redirect()->route('perfil.password.edit')
                     ->with('status', '¡Contraseña actualizada exitosamente!');
    }
}
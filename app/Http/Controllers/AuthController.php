<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use App\Models\User; // Importamos nuestro modelo User
use Illuminate\Routing\Controller as BaseController; // <-- 1. LÍNEA AÑADIDA

use Spatie\Activitylog\Facades\Activity;

class AuthController extends BaseController // <-- 2. LÍNEA CAMBIADA
{
    /**
     * Muestra el formulario de login (CU-001 - Vista)
     */
    public function showLogin()
    {
        // Simplemente devuelve la vista que crearemos después
        return view('auth.login');
    }

    /**
     * Procesa el intento de login (CU-001 - Lógica)
     */
    public function login(Request $request): RedirectResponse
    {
        // 1. Validar los datos del formulario
        $credentials = $request->validate([
            'nombre' => ['required', 'string'],      // Usamos 'nombre' para el username (CI)
            'contrasena' => ['required', 'string'], // Usamos 'contrasena' para el password
        ]);

        // 2. Preparar las credenciales para el intento de login
        // Debemos decirle a Laravel que nuestro campo de password se llama 'contrasena'
        $authCredentials = [
            'nombre' => $credentials['nombre'],
            'password' => $credentials['contrasena'], // Auth::attempt espera 'password'
            'activo' => true, // Requerir que el usuario esté activo
        ];

        // 3. Intentar autenticar al usuario
       if (Auth::attempt($authCredentials, false)) {
            $request->session()->regenerate();

            // 4. Redirigir según el rol
            $user = Auth::user(); // Obtenemos el usuario autenticado
            //bitacora
            Activity::causedBy($user)
                    ->log('Inicio de Sesión');
            // Cargamos la relación 'rol' que definimos en el Modelo User.php
            $userRole = $user->rol?->nombre; // Usamos ?-> para evitar error si no tiene rol

            // (Opcional: registrar en bitácora)
            // Bitacora::create([ 'usuario_id' => $user->id, 'accion' => 'Login', ... ]);

            switch (strtolower($userRole)) {
                case 'administrador':
                    return redirect()->intended(route('admin.dashboard')); // (Ruta que crearemos)
                case 'coordinador':
                    return redirect()->intended(route('coordinador.dashboard')); // (Ruta que crearemos)
                case 'docente':
                    return redirect()->intended(route('docente.dashboard')); // (Ruta que crearemos)
                case 'autoridad':
                    return redirect()->intended(route('autoridad.dashboard')); // (Ruta que crearemos)
                default:
                    return redirect()->intended(route('home'));
            }
        }

        // 5. Si la autenticación falla
        throw ValidationException::withMessages([
            'nombre' => 'Las credenciales proporcionadas no son correctas.',
        ])->redirectTo(route('login'));
    }

    /**
     * Procesa el logout (CU-002)
     */
    public function logout(Request $request): RedirectResponse
    {
        $userId = Auth::id(); // Obtener el ID antes de hacer logout
        Activity::causedBy(Auth::user()) // Captura al usuario ANTES de que se vaya
                ->log('Cierre de Sesión');

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // (Opcional: registrar en bitácora)
        // Bitacora::create([ 'usuario_id' => $userId, 'accion' => 'Logout', ... ]);

        return redirect(route('login'));
    }
}
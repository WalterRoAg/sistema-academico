<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Necesitamos importar Auth
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $rolRequerido): Response
    {
        // 1. Obtenemos el rol del usuario que está logueado
        //    Usamos ?-> para evitar errores si el rol es nulo
        $rolUsuario = Auth::user()?->rol?->nombre;

        // 2. Comparamos el rol del usuario con el rol que requiere la ruta
        if (strtolower($rolUsuario) === strtolower($rolRequerido)) {
            // 3. Si coinciden (ej. 'coordinador' === 'coordinador'), lo dejamos pasar
            return $next($request);
        }

        // 4. Si no coinciden, lo bloqueamos
        //    abort(403) es el error "Prohibido" (Forbidden)
        abort(403, 'Acceso No Autorizado.');
    }
}
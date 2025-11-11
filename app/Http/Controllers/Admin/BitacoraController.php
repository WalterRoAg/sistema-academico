<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity; // <-- El modelo de la bitácora
use App\Models\User;
use App\Models\Rol;

class BitacoraController extends Controller
{
    /**
     * Muestra la bitácora con filtros (CU-16).
     */
    public function index(Request $request)
    {
        // 1. Empezamos la consulta, cargando al "causante" (User)
        // y sus relaciones (Persona y Rol) para mostrar en la tabla.
        $query = Activity::with(['causer.persona', 'causer.rol'])
                         ->orderBy('created_at', 'desc');

        // 2. APLICAR FILTROS (CU-16)

        // Filtro: Rango de Fechas
        if ($request->filled('fecha_inicio')) {
            $query->whereDate('created_at', '>=', $request->fecha_inicio);
        }
        if ($request->filled('fecha_fin')) {
            $query->whereDate('created_at', '<=', $request->fecha_fin);
        }

        // Filtro: Usuario (causer_id)
        if ($request->filled('user_id')) {
            $query->where('causer_id', $request->user_id);
        }

        // Filtro: Rol (a través de la relación)
        if ($request->filled('rol_id')) {
            $query->whereHas('causer.rol', function($q) use ($request) {
                $q->where('id', $request->rol_id);
            });
        }

        // Filtro: Acción (description)
        if ($request->filled('accion')) {
            // 'description' guarda 'created', 'updated', 'Inicio de Sesión', etc.
            $query->where('description', 'like', '%' . $request->accion . '%');
        }

        // Filtro: IP (buscamos en el JSON 'properties')
        if ($request->filled('ip_address')) {
            $query->where('properties->ip_address', 'like', '%' . $request->ip_address . '%');
        }

        // 3. Obtener datos para los <select> del formulario
        $usuarios = User::with('persona')->orderBy('nombre')->get();
        $roles = Rol::orderBy('nombre')->get();
        
        // Obtenemos solo las acciones únicas que SÍ existen en la bitácora
        $acciones = Activity::select('description')->distinct()->pluck('description');

        // 4. Paginar los resultados
        // withQueryString() hace que los filtros se mantengan al cambiar de página
        $bitacora = $query->paginate(25)->withQueryString();

        return view('admin.bitacora.index', compact(
            'bitacora', 
            'usuarios', 
            'roles', 
            'acciones'
        ));
    }
}
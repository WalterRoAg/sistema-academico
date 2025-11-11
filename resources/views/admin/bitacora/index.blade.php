<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bitácora del Sistema</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4 md:p-10">
    <h1 class="text-2xl font-bold mb-4">Bitácora del Sistema (CU-16)</h1>

    <div class="bg-white p-6 rounded-xl shadow border mb-6">
        <form action="{{ route('admin.bitacora.index') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700">Usuario</label>
                    <select name="user_id" id="user_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                        <option value="">(Todos los Usuarios)</option>
                        @foreach ($usuarios as $usuario)
                            <option value="{{ $usuario->id }}" @selected(request('user_id') == $usuario->id)>
                                {{ $usuario->persona?->nombre ?? $usuario->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="rol_id" class="block text-sm font-medium text-gray-700">Rol</label>
                    <select name="rol_id" id="rol_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                        <option value="">(Todos los Roles)</option>
                        @foreach ($roles as $rol)
                            <option value="{{ $rol->id }}" @selected(request('rol_id') == $rol->id)>
                                {{ $rol->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="accion" class="block text-sm font-medium text-gray-700">Acción</label>
                    <select name="accion" id="accion" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                        <option value="">(Todas las Acciones)</option>
                        @foreach ($acciones as $accion)
                            <option value="{{ $accion }}" @selected(request('accion') == $accion)>
                                {{ ucfirst($accion) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="ip_address" class="block text-sm font-medium text-gray-700">Dirección IP</label>
                    <input type="text" name="ip_address" id="ip_address" value="{{ request('ip_address') }}"
                           placeholder="Ej: 127.0.0.1"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                </div>

                <div>
                    <label for="fecha_inicio" class="block text-sm font-medium text-gray-700">Desde</label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio" value="{{ request('fecha_inicio') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                </div>
                <div>
                    <label for="fecha_fin" class="block text-sm font-medium text-gray-700">Hasta</label>
                    <input type="date" name="fecha_fin" id="fecha_fin" value="{{ request('fecha_fin') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                </div>
            </div>

            <div class="flex gap-4 pt-6">
                <button type="submit" 
                        class="rounded-lg bg-indigo-600 text-white px-4 py-2 hover:bg-indigo-700">
                    Filtrar
                </button>
                <a href="{{ route('admin.bitacora.index') }}" class="text-gray-600 hover:underline self-center">
                    Limpiar Filtros
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow border overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha y Hora</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usuario</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rol</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acción</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Objeto Afectado</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">IP</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($bitacora as $log)
                    <tr class="text-sm text-gray-700">
                        <td class="px-4 py-3">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                        <td class="px-4 py-3">{{ $log->causer?->persona?->nombre ?? ($log->causer?->nombre ?? 'Sistema') }}</td>
                        <td class="px-4 py-3">{{ $log->causer?->rol?->nombre ?? 'N/A' }}</td>
                        <td class="px-4 py-3 font-medium">
                            {{ ucfirst($log->description) }}
                        </td>
                        <td class="px-4 py-3">
                            {{ $log->subject_type ? str_replace('App\Models\\', '', $log->subject_type) : '' }}
                            {{ $log->subject_id ? '(ID: ' . $log->subject_id . ')' : '' }}
                        </td>
                        <td class="px-4 py-3 font-mono">
                            {{ $log->properties->get('ip_address') ?? 'N/A' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                            No se encontraron registros con los filtros seleccionados.
                        </td>
                    </tr>
                @endforelse </tbody>
        </table>
    </div>
    
    <div class="mt-6">
        {{ $bitacora->links() }}
    </div>
    </div>
        <a href="{{ route('admin.dashboard') }}" class="mt-6 inline-block text-indigo-600 hover:underline">&larr; Volver al Dashboard</a>
    </div>
</body>
</html>
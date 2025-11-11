<!DOCTYPE html>
<html lang="es">
<head>
    <title>Gestionar Usuarios</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Gestionar Usuarios (CU-13)</h1>
           <!-- <a href="{{-- {{ route('admin.usuarios.create') }} --}}" class="rounded-lg bg-indigo-600 text-white px-4 py-2 hover:bg-indigo-700">
                Añadir Usuario
            </a>-->
        </div>

        @if (session('status'))
            <div class="mb-4 rounded-lg bg-green-100 p-4 text-sm font-medium text-green-700" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow border overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usuario (CI)</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre Completo</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Correo</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rol</th>
                        
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Característica</th>
                        
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($usuarios as $usuario)
                        <tr class="text-sm text-gray-700">
                            <td class="px-4 py-3 font-medium">{{ $usuario->nombre }}</td>
                            <td class="px-4 py-3">{{ $usuario->persona?->nombre }}</td>
                            <td class="px-4 py-3">{{ $usuario->correo }}</td>
                            <td class="px-4 py-3">{{ $usuario->rol?->nombre }}</td>
                            
                            <td class="px-4 py-3">{{ $usuario->caracteristica }}</td>

                            <td class="px-4 py-3">
                                @if ($usuario->activo)
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Activo
                                    </span>
                                @else
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Inactivo
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.usuarios.edit', $usuario) }}" class="text-indigo-600 hover:underline">
                                    Editar
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                                No se encontraron usuarios.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="mt-6 inline-block text-indigo-600 hover:underline">&larr; Volver al Dashboard</a>
    </div>
</body>
</html>
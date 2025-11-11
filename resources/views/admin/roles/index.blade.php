<!DOCTYPE html>
<html lang="es">
<head>
    <title>Gestionar Roles</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Gestionar Roles (CU-01)</h1>
            <!-- <a href="{{ route('admin.roles.create') }}" class="rounded-lg bg-indigo-600 text-white px-4 py-2 hover:bg-indigo-700">
                Añadir Rol
            </a> -->
        </div>

        @if (session('status'))
            <div class="mb-4 rounded-lg bg-green-100 p-4 text-sm font-medium text-green-700" role="alert">
                {{ session('status') }}
            </div>
        @endif

       <!-- <div class="mb-4 rounded-lg bg-blue-100 p-4 text-sm text-blue-800" role="alert">
            <p><strong class="font-bold">Nota:</strong> La "Característica (del Rol)" es la descripción general. El cargo individual (ej. "Decano") se gestiona en la tabla de <a href="{{ route('admin.usuarios.index') }}" class="font-bold underline">Gestionar Usuarios</a>.</p>
        </div> -->


        <div class="bg-white rounded-xl shadow border overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID (para Excel)</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Característica (del Rol)</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($roles as $rol)
                        <tr class="text-sm text-gray-700">
                            <td class="px-4 py-3 font-mono">{{ $rol->id }}</td>
                            <td class="px-4 py-3 font-medium">{{ $rol->nombre }}</td>
                            <td class="px-4 py-3">{{ $rol->caracteristica }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.roles.edit', $rol) }}" class="text-indigo-600 hover:underline">
                                    Editar
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-gray-500">
                                No se encontraron roles.
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
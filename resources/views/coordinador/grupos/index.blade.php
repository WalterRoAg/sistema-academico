<!DOCTYPE html>
<html lang="es">
<head>
    <title>Gestionar Grupos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
    <h1 class="text-2xl font-bold mb-4">Gestión de Grupos (CU-006)</h1>

    @if (session('status'))
        <div class="mb-4 rounded-lg bg-green-100 p-4 text-sm font-medium text-green-700">
            {{ session('status') }}
        </div>
    @endif

    <div class="mb-4">
        <a href="{{ route('coordinador.grupos.create') }}" 
           class="rounded-lg bg-indigo-600 text-white px-4 py-2 font-medium hover:bg-indigo-700">
           Registrar Nuevo Grupo
        </a>
    </div>

    <div class="bg-white p-6 rounded-xl shadow border max-w-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b">
                        <th class="py-2 px-4 text-left">ID</th>
                        <th class="py-2 px-4 text-left">Nombre (SA, SB...)</th>
                        <th class="py-2 px-4 text-left">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($grupos as $grupo)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-2 px-4">{{ $grupo->id }}</td>
                            <td class="py-2 px-4">{{ $grupo->nombre }}</td>
                            <td class="py-2 px-4">
                                <a href="{{ route('coordinador.grupos.edit', $grupo) }}" class="text-indigo-600 hover:underline">Modificar</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-4 text-center text-gray-500">
                                No hay grupos registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <a href="{{ route('coordinador.dashboard') }}" class="mt-4 inline-block text-indigo-600 hover:underline">&larr; Volver al Dashboard</a>
</body>
</html>
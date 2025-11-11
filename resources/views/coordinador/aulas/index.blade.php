<!DOCTYPE html>
<html lang="es">
<head>
    <title>Gestionar Aulas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
    <h1 class="text-2xl font-bold mb-4">Gestión de Aulas (CU-05)</h1>
        @if (session('status'))
    <div class="mb-4 rounded-lg bg-green-100 p-4 text-sm font-medium text-green-700">
        {{ session('status') }}
    </div>
@endif

<div class="mb-4">
    <a href="{{ route('coordinador.aulas.create') }}" 
       class="rounded-lg bg-indigo-600 text-white px-4 py-2 font-medium hover:bg-indigo-700">
       Registrar Nueva Aula
    </a>
</div>
    <div class="bg-white p-6 rounded-xl shadow border">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="border-b">
                    <th class="py-2 px-4 text-left">ID</th>
                    <th class="py-2 px-4 text-left">Piso</th>
                    <th class="py-2 px-4 text-left">Número</th>
                    <th class="py-2 px-4 text-left">Capacidad</th>
                    <th class="py-2 px-4 text-left">Estado</th>
                    <th class="py-2 px-4 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($aulas as $aula)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-2 px-4">{{ $aula->id }}</td>
                        <td class="py-2 px-4">{{ $aula->piso }}</td>
                        <td class="py-2 px-4">{{ $aula->numero }}</td>
                        <td class="py-2 px-4">{{ $aula->capacidad }}</td>
                        <td class="py-2 px-4">
                            @if($aula->activo)
                                <span class="font-medium text-green-600">Activo</span>
                            @else
                                <span class="font-medium text-red-600">Inactivo</span>
                            @endif
                        </td>
                        <td class="py-2 px-4">
                            <a href="{{ route('coordinador.aulas.edit', $aula) }}" class="text-indigo-600 hover:underline">Modificar</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-4 text-center text-gray-500">
                            No hay aulas registradas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <a href="{{ route('coordinador.dashboard') }}" class="mt-4 inline-block text-indigo-600 hover:underline">&larr; Volver al Dashboard</a>
</body>
</html>
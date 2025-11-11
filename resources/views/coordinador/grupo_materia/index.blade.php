<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apertura de Materias</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4 md:p-10">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl md:text-3xl font-bold mb-4">Apertura de Materias por Gestión</h1>
        <p class="mb-4 text-gray-700">Usando la gestión activa: <span class="font-semibold text-blue-600">{{ $periodo->nombre }}</span></p>

        <!-- Mensajes de Estado (Status y Error) -->
        @if (session('status'))
            <div class="mb-4 rounded-lg bg-green-100 p-4 text-sm font-medium text-green-700" role="alert">
                {{ session('status') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 rounded-lg bg-red-100 p-4 text-sm font-medium text-red-700" role="alert">
                {{ session('error') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-100 p-4 text-sm text-red-700" role="alert">
                <p class="font-bold">Ocurrieron los siguientes errores:</p>
                <ul class="list-disc pl-5 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- 1. Formulario para "Abrir" Materias -->
        <div class="bg-white p-6 rounded-xl shadow border mb-6">
            <h2 class="text-xl font-bold mb-4 border-b pb-2">Abrir Nueva Combinación Materia/Grupo</h2>
            <form action="{{ route('coordinador.grupo-materia.store') }}" method="POST">
                @csrf
                <input type="hidden" name="periodo_id" value="{{ $periodo->id }}">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    
                    <div>
                        <label for="materia_sigla" class="block text-sm font-medium text-gray-700">Materia</label>
                        <select id="materia_sigla" name="materia_sigla" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Seleccione materia...</option>
                            @foreach ($materias as $materia)
                                <option value="{{ $materia->sigla }}" @selected(old('materia_sigla') == $materia->sigla)>
                                    {{ $materia->sigla }} - {{ $materia->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="grupo_id" class="block text-sm font-medium text-gray-700">Grupo</label>
                        <select id="grupo_id" name="grupo_id" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Seleccione grupo...</option>
                            @foreach ($grupos as $grupo)
                                <option value="{{ $grupo->id }}" @selected(old('grupo_id') == $grupo->id)>
                                    {{ $grupo->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="w-full rounded-lg bg-indigo-600 text-white px-5 py-2 hover:bg-indigo-700 font-medium">
                            Abrir Combinación
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- 2. Tabla de Materias ya Abiertas -->
        <div class="bg-white rounded-xl shadow border">
            <h2 class="text-xl font-bold p-6">Materias Abiertas en esta Gestión ({{ $periodo->nombre }})</h2>
            
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr class="border-t">
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">Materia (Sigla)</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">Nombre</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">Grupo</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($materiasAbiertas as $gm)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-mono">{{ $gm->materia->sigla }}</td>
                                <td class="px-4 py-3">{{ $gm->materia->nombre }}</td>
                                <td class="px-4 py-3">{{ $gm->grupo->nombre }}</td>
                                <td class="px-4 py-3">
                                    <form action="{{ route('coordinador.grupo-materia.destroy', $gm->id) }}" method="POST" onsubmit="return confirm('¿Está seguro que desea CERRAR esta combinación?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Cerrar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-gray-500">
                                    Aún no se han abierto materias para esta gestión. (Datos del Seeder no encontrados)
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <a href="{{ route('coordinador.dashboard') }}" class="mt-6 inline-block text-indigo-600 hover:underline">&larr; Volver al Dashboard</a>
    </div>
</body>
</html>
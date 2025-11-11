<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consultar Horarios (CU-07)</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4 md:p-10">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Consultar Horarios de Docentes (CU-07)</h1>
            <a href="{{ route('autoridad.dashboard') }}" class="text-sm text-gray-600 hover:underline">
                &larr; Volver al Dashboard
            </a>
        </div>

        <div class="bg-white p-6 rounded-xl shadow border mb-6">
            <form action="{{ route('autoridad.horarios.index') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="docente_id" class="block text-sm font-medium text-gray-700">Filtrar por Docente</label>
                        <select name="docente_id" id="docente_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                            <option value="">(Todos los Docentes)</option>
                            @foreach ($docentes as $docente)
                                <option value="{{ $docente->id }}" @selected(request('docente_id') == $docente->id)>
                                    {{ $docente->persona->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="materia_id" class="block text-sm font-medium text-gray-700">Filtrar por Materia</label>
                        <select name="materia_id" id="materia_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                            <option value="">(Todas las Materias)</option>
                            @foreach ($materias as $materia)
                                <option value="{{ $materia->id }}" @selected(request('materia_id') == $materia->id)>
                                    {{ $materia->nombre }} ({{ $materia->sigla }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex gap-4 pt-6">
                    <button type="submit" 
                            class="rounded-lg bg-indigo-600 text-white px-4 py-2 hover:bg-indigo-700">
                        Filtrar Horarios
                    </button>
                    <a href="{{ route('autoridad.horarios.index') }}" class="text-gray-600 hover:underline self-center">
                        Limpiar Filtros
                    </a>
                </div>
            </form>
        </div>

        @forelse ($horariosClase->all() as $personaId => $horariosDocente)
            
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-3 p-3 bg-gray-100 rounded-lg">
                    Docente: {{ $horariosDocente->first()->docente->persona->nombre }}
                </h2>

                <div class="bg-white rounded-xl shadow border overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Día</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Horario</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Materia (Grupo)</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aula</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($horariosDocente as $horarioClase)
                                <tr class="text-sm text-gray-700">
                                    <td class="px-4 py-3 font-medium">{{ $horarioClase->horario->dia }}</td>
                                    <td class="px-4 py-3">{{ $horarioClase->horario->hora_ini->format('H:i') }} - {{ $horarioClase->horario->hora_fin->format('H:i') }}</td>
                                    <td class="px-4 py-3">
                                        {{ $horarioClase->grupoMateria->materia->nombre }} 
                                        (G: {{ $horarioClase->grupoMateria->grupo->nombre }})
                                    </td>
                                    <td class="px-4 py-3">{{ $horarioClase->aula->numero }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        
        @empty
            <div class="bg-white p-6 rounded-xl shadow border text-center">
                <p class="text-gray-500">No se encontraron horarios con los filtros seleccionados.</p>
            </div>
        @endforelse
    </div>
</body>
</html>
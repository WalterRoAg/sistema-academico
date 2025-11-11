<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planificación de Horarios</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4 md:p-10">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-2xl md:text-3xl font-bold mb-4">Planificación Académica (CU-07 y CU-08)</h1>
        <p class="mb-4 text-gray-700">Gestión Activa: <span class="font-semibold text-blue-600">{{ $periodo->nombre }}</span> ({{ $periodo->fecha_inicio->format('d/m/Y') }} al {{ $periodo->fecha_fin->format('d/m/Y') }})</p>

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

        <div class="bg-white p-6 rounded-xl shadow border mb-6">
            <h2 class="text-xl font-bold mb-4 border-b pb-2">Asignar Clase (CU-08)</h2>
            <form action="{{ route('coordinador.planificacion.store') }}" method="POST">
                @csrf
                <input type="hidden" name="periodo_id" value="{{ $periodo->id }}">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div>
                        <label for="horario_id" class="block text-sm font-medium text-gray-700">Día y Hora (Bloque)</label>
                        <select id="horario_id" name="horario_id" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Seleccione un bloque...</option>
                            @foreach ($horariosBase as $h)
                                <option value="{{ $h->id }}" @selected(old('horario_id') == $h->id)>
                                    {{ $h->dia }} ({{ $h->hora_ini->format('H:i') }} - {{ $h->hora_fin->format('H:i') }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="aula_id" class="block text-sm font-medium text-gray-700">Aula</label>
                        <select id="aula_id" name="aula_id" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Seleccione un aula...</option>
                            @foreach ($aulas as $aula)
                                <option value="{{ $aula->id }}" @selected(old('aula_id') == $aula->id)>
                                    {{ $aula->numero }} (Cap: {{ $aula->capacidad }})
                                </option>
                            @endforeach
                        </select>
                    </div>

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

                    <div>
                        <label for="docente_persona_id" class="block text-sm font-medium text-gray-700">Docente</label>
                        <select id="docente_persona_id" name="docente_persona_id" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Seleccione un docente...</option>
                            @foreach ($docentes as $docente)
                                <option value="{{ $docente->persona_id }}" @selected(old('docente_persona_id') == $docente->persona_id)>
                                    {{ $docente->persona->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <button type="submit" class="rounded-lg bg-indigo-600 text-white px-5 py-2 hover:bg-indigo-700 font-medium">
                        Asignar Clase
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow border">
            <h2 class="text-xl font-bold p-6">Vista Semanal (CU-07)</h2>
            
            <div class="px-6 pb-4 border-b">
                <form action="{{ route('coordinador.planificacion.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="filtro_docente_id" class="block text-sm font-medium text-gray-700">Filtrar por Docente</label>
                        <select id="filtro_docente_id" name="docente_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">-- Todos los Docentes --</option>
                            @foreach ($docentes as $docente)
                                <option value="{{ $docente->persona_id }}" @selected($filtroDocente == $docente->persona_id)>
                                    {{ $docente->persona->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="filtro_grupo_id" class="block text-sm font-medium text-gray-700">Filtrar por Grupo</label>
                        <select id="filtro_grupo_id" name="grupo_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">-- Todos los Grupos --</option>
                            @foreach ($grupos as $grupo)
                                <option value="{{ $grupo->id }}" @selected($filtroGrupo == $grupo->id)>
                                    {{ $grupo->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="rounded-lg bg-blue-600 text-white px-5 py-2 hover:bg-blue-700 font-medium">
                            Filtrar
                        </button>
                        <a href="{{ route('coordinador.planificacion.index') }}" class="rounded-lg bg-gray-200 text-gray-700 px-5 py-2 hover:bg-gray-300 font-medium">
                            Limpiar
                        </a>
                    </div>
                </form>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="sticky left-0 z-20 bg-gray-100 border-r px-3 py-3 text-xs font-medium uppercase text-gray-500">Hora</th>
                            @foreach ($dias_semana as $dia)
                                <th class="min-w-[12rem] px-3 py-3 text-center text-xs font-medium uppercase text-gray-500">{{ $dia }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($horas_del_dia as $hora)
                            <tr class="border-b">
                                <td class="sticky left-0 z-10 min-w-[5rem] border-r bg-gray-100 px-3 py-2 text-center text-xs font-medium text-gray-600">
                                    {{ $hora }}
                                </td>

                                @foreach ($dias_semana as $dia)
                                    @php
                                        $slot = $slotsAsignados->first(function ($item) use ($dia, $hora) {
                                            return $item->horario->dia === $dia && 
                                                   $item->horario->hora_ini->format('H:i') === $hora;
                                        });
                                    @endphp

                                    <td class="border-r align-top {{ $loop->last ? 'border-r-0' : '' }}">
                                        @if ($slot)
                                            <div class="p-2 text-xs text-gray-700 bg-indigo-50 m-1 border border-indigo-200 rounded">
                                                <p class="font-bold text-indigo-700">
                                                    {{ $slot->grupoMateria->materia->sigla }} - G: {{ $slot->grupoMateria->grupo->nombre }}
                                                </p>
                                                <p>{{ $slot->docente->persona->nombre }}</p>
                                                <p class="text-gray-500">Aula: {{ $slot->aula->numero }}</p>
                                                
                                                <form action="{{ route('coordinador.planificacion.destroy', $slot->id) }}" method="POST"
                                                      class="mt-2"
                                                      onsubmit="return confirm('¿Está seguro de que desea eliminar esta asignación?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 font-medium text-xs">
                                                        Eliminar
                                                    </button>
                                                </form>
                                                </div>
                                        @else
                                            <div class="h-16"></div>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <a href="{{ route('coordinador.dashboard') }}" class="mt-6 inline-block text-indigo-600 hover:underline">&larr; Volver al Dashboard</a>
    </div>
</body>
</html>
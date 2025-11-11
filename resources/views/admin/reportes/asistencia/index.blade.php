<!DOCTYPE html>
<html lang="es">
<head>
    <title>Reporte de Asistencia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>
<body class="bg-gray-100 p-10">
    <h1 class="text-2xl font-bold mb-4">Generar Reporte de Asistencia (CU-10)</h1>

    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-red-100 p-4 text-sm font-medium text-red-700">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white p-6 rounded-xl shadow border max-w-2xl">
        <form action="{{ route('admin.reportes.asistencia.generar') }}" method="GET" class="space-y-4">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="fecha_inicio" class="block text-sm font-medium text-gray-700">Fecha Inicio (*)</label>
                    <input type="date" id="fecha_inicio" name="fecha_inicio" value="{{ old('fecha_inicio') }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label for="fecha_fin" class="block text-sm font-medium text-gray-700">Fecha Fin (*)</label>
                    <input type="date" id="fecha_fin" name="fecha_fin" value="{{ old('fecha_fin') }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="docente_id" class="block text-sm font-medium text-gray-700">Filtrar por Docente</label>
                    <select id="docente_id" name="docente_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">(Todos los docentes)</option>
                        @foreach ($docentes as $docente)
                            <option value="{{ $docente->id }}" {{ old('docente_id') == $docente->id ? 'selected' : '' }}>
                                {{ $docente->persona->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="materia_id" class="block text-sm font-medium text-gray-700">Filtrar por Materia</label>
                    <select id="materia_id" name="materia_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">(Todas las materias)</option>
                        @foreach ($materias as $materia)
                            <option value="{{ $materia->id }}" {{ old('materia_id') == $materia->id ? 'selected' : '' }}>
                                {{ $materia->nombre }} ({{ $materia->sigla }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Formato de Exportación (*)</label>
                <div class="mt-2 space-x-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="formato" value="pdf" class="text-indigo-600" checked>
                        <span class="ml-2">PDF (.pdf)</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="formato" value="excel" class="text-indigo-600">
                        <span class="ml-2">Excel (.xlsx)</span>
                    </label>
                </div>
            </div>

            <div class="flex gap-4 pt-4">
                <button type="submit" 
                        class="rounded-lg bg-indigo-600 text-white px-4 py-2 hover:bg-indigo-700">
                    Generar Reporte
                </button>
                <a href="{{ url()->previous() }}" class="text-gray-600 hover:underline self-center">
                    Cancelar
                </a>
            </div>

        </form>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        flatpickr("input[type=date]", {
            dateFormat: "Y-m-d",
        });
    </script>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="mt-6 inline-block text-indigo-600 hover:underline">&larr; Volver al Dashboard</a>
        </div>
</body>
</html>
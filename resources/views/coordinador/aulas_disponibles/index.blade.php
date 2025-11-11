<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Aulas Disponibles</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4 md:p-10">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Consultar Aulas Disponibles (CU-18)</h1>
            <a href="{{ route('coordinador.dashboard') }}" class="text-sm text-gray-600 hover:underline">
                &larr; Volver al Dashboard
            </a>
        </div>

        <div class="bg-white p-6 rounded-xl shadow border mb-6">
            <form action="{{ route('coordinador.aulas.disponibles') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="horario_id" class="block text-sm font-medium text-gray-700">Seleccionar Horario</label>
                        <select name="horario_id" id="horario_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                            <option value="">(Seleccione un horario para ver estado)</option>
                            @foreach ($horarios as $horario)
                                <option value="{{ $horario->id }}" 
                                        @selected(request('horario_id') == $horario->id)>
                                    {{ $horario->dia }} 
                                    ({{ $horario->hora_ini->format('H:i') }} - {{ $horario->hora_fin->format('H:i') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex gap-4 pt-6">
                    <button type="submit" 
                            class="rounded-lg bg-indigo-600 text-white px-4 py-2 hover:bg-indigo-700">
                        Filtrar Estado
                    </button>
                    <a href="{{ route('coordinador.aulas.disponibles') }}" class="text-gray-600 hover:underline self-center">
                        Mostrar Todas
                    </a>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow border overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Número</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Capacidad</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">-</th>
                        
                        <th class="px-0 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($aulas as $aula)
                        <tr class="text-sm text-gray-700">
                            <td class="px-4 py-3 font-medium">{{ $aula->numero }}</td>
                            <td class="px-4 py-3">{{ $aula->capacidad }}</td>
                            <td class="px-4 py-3">{{ $aula->tipo }}</td>
                            
                            <td class="px-2 py-3">
                                @if(request('horario_id') && $aulasOcupadasIds->contains($aula->id))
                                    <span class="px-4 py-3 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Ocupado
                                    </span>
                                @else
                                    <span class="px-4 py-3 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Libre
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-gray-500">
                                No hay aulas registradas en el sistema.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
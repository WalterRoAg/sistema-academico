<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Horario</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4 md:p-10">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-2xl md:text-3xl font-bold mb-4">Mi Horario Asignado (CU-07)</h1>
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

        <div class="bg-white rounded-xl shadow border">
            <h2 class="text-xl font-bold p-6">Vista Semanal</h2>
            
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
                                            // Compara el día Y la hora de inicio del bloque
                                            return $item->horario->dia === $dia && 
                                                   $item->horario->hora_ini->format('H:i') === $hora;
                                        });
                                    @endphp

                                    <td class="border-r align-top {{ $loop->last ? 'border-r-0' : '' }}">
                                        @if ($slot)
                                            {{-- Si encontramos un slot, mostramos sus detalles --}}
                                            <div class="p-2 text-xs text-gray-700 bg-blue-50 m-1 border border-blue-200 rounded">
                                                <p class="font-bold text-blue-700">
                                                    {{ $slot->grupoMateria->materia->sigla }} - G: {{ $slot->grupoMateria->grupo->nombre }}
                                                </p>
                                                <p class="text-gray-500">Aula: {{ $slot->aula->numero }}</p>
                                            </div>
                                        @else
                                            {{-- Celda vacía --}}
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

        <a href="{{ route('docente.dashboard') }}" class="mt-6 inline-block text-indigo-600 hover:underline">&larr; Volver al Dashboard</a>
    </div>
</body>
</html>
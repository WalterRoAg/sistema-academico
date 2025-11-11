<!DOCTYPE html>
<html lang="es">
<head>
    <title>Gestionar Docentes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
    <h1 class="text-2xl font-bold mb-4">Gestión de Docentes (CU-03)</h1>

    @if (session('status'))
        <div class="mb-4 rounded-lg bg-green-100 p-4 text-sm font-medium text-green-700">
            {{ session('status') }}
        </div>
    @endif

    <div class="mb-4">
       <!-- <a href="{{ route('coordinador.docentes.create') }}" 
           class="rounded-lg bg-green-600 text-white px-4 py-2 font-medium hover:bg-green-700">
           Registrar Nuevo Docente
        </a> -->
    </div>

    <div class="bg-white p-6 rounded-xl shadow border">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b">
                        <th class="py-2 px-4 text-left">Carnet (CI)</th>
                        <th class="py-2 px-4 text-left">Nombre Completo</th>
                        <th class="py-2 px-4 text-left">Teléfono</th>
                        <th class="py-2 px-4 text-left">Años Exp.</th>
                        <th class="py-2 px-4 text-left">Fecha Ingreso</th>
                        <th class="py-2 px-4 text-left">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($docentes as $docente)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-2 px-4">{{ $docente->persona?->carnet }}</td>
                            <td class="py-2 px-4">{{ $docente->persona?->nombre }}</td>
                            <td class="py-2 px-4">{{ $docente->persona?->telefono }}</td>
                            <td class="py-2 px-4">{{ $docente->anos_experiencia }}</td>
                            
                            <td class="py-2 px-4">{{ $docente->fecha_ingreso }}
                                @if($docente->fecha_ingreso && \Carbon\Carbon::parse($docente->fecha_ingreso)->isFuture())
                                    <span class="inline-block px-2 py-0.5 text-xs font-semibold bg-yellow-200 text-yellow-800 rounded-full">
                                        Pendiente
                                    </span>
                                @endif
                            </td>
                            
                            <td class="py-2 px-4">
                                <a href="{{ route('coordinador.docentes.edit', $docente) }}" class="text-indigo-600 hover:underline">Modificar</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 text-center text-gray-500">
                                No hay docentes registrados.
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
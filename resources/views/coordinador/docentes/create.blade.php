<!DOCTYPE html>
<html lang="es">
<head>
    <title>Registrar Docente</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
    <h1 class="text-2xl font-bold mb-4">Registrar Nuevo Docente (CU-03)</h1>

    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-red-100 p-4 text-sm text-red-700">
            <ul class="list-disc pl-5 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white p-6 rounded-xl shadow border max-w-3xl">
        <form action="{{ route('coordinador.docentes.store') }}" method="POST" class="space-y-4">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="carnet" class="block text-sm font-medium text-gray-700">Carnet de Identidad (CI)</label>
                    <input type="text" id="carnet" name="carnet" value="{{ old('carnet') }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                </div>

                <div>
                    <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre Completo</label>
                    <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                </div>

                <div>
                    <label for="telefono" class="block text-sm font-medium text-gray-700">Teléfono (Opcional)</label>
                    <input type="text" id="telefono" name="telefono" value="{{ old('telefono') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                </div>

                <div>
                    <label for="anos_experiencia" class="block text-sm font-medium text-gray-700">Años Experiencia</label>
                    <input type="number" id="anos_experiencia" name="anos_experiencia" value="{{ old('anos_experiencia') }}" min="0"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                </div>

                <div>
                    <label for="fecha_ingreso" class="block text-sm font-medium text-gray-700">Fecha Ingreso</label>
                    <input type="date" id="fecha_ingreso" name="fecha_ingreso" value="{{ old('fecha_ingreso', date('Y-m-d')) }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                </div>
            </div>

            <div class="border-t pt-4 mt-4">
                <p class="font-semibold text-gray-700 mb-2">Información Profesional (Titulación)</p>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="profesion_id" class="block text-sm font-medium text-gray-700">Título Principal</label>
                        <select id="profesion_id" name="profesion_id" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                            <option value="">Seleccione una profesión</option>
                            @foreach ($profesiones as $profesion)
                                <option value="{{ $profesion->id }}" @selected(old('profesion_id') == $profesion->id)>
                                    {{ $profesion->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="nivel_profesional" class="block text-sm font-medium text-gray-700">Nivel (Ej. Licenciatura)</label>
                        <input type="text" id="nivel_profesional" name="nivel_profesional" value="{{ old('nivel_profesional') }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                    </div>
                </div>
            </div>

            <div class="flex gap-4 pt-4">
                <button type="submit" class="rounded-lg bg-green-600 text-white px-4 py-2 hover:bg-green-700">Registrar Docente</button>
                <a href="{{ route('coordinador.docentes.index') }}" class="text-gray-600 hover:underline self-center">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>
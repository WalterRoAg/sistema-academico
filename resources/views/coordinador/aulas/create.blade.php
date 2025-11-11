<!DOCTYPE html>
<html lang="es">
<head>
    <title>Registrar Aula</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
    <h1 class="text-2xl font-bold mb-4">Registrar Nueva Aula (CU-005)</h1>

    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-red-100 p-4 text-sm text-red-700">
            <p class="font-bold">¡Ups! Hubo algunos problemas con tus datos:</p>
            <ul class="list-disc pl-5 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white p-6 rounded-xl shadow border max-w-2xl">
        <form action="{{ route('coordinador.aulas.store') }}" method="POST" class="space-y-4">
            @csrf <div>
                <label for="numero" class="block text-sm font-medium text-gray-700">Número de Aula</label>
                <input type="text" id="numero" name="numero" value="{{ old('numero') }}" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>

            <div>
                <label for="piso" class="block text-sm font-medium text-gray-700">Piso</label>
                <input type="number" id="piso" name="piso" value="{{ old('piso') }}" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>

            <div>
                <label for="capacidad" class="block text-sm font-medium text-gray-700">Capacidad</label>
                <input type="number" id="capacidad" name="capacidad" value="{{ old('capacidad', 30) }}" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>

            <div>
                <label for="activo" class="block text-sm font-medium text-gray-700">Estado</label>
                <select id="activo" name="activo" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="1" selected>Activo</option>
                    <option value="0">Inactivo</option>
                </select>
            </div>

            <div class="flex gap-4 pt-4">
                <button type="submit" 
                        class="rounded-lg bg-indigo-600 text-white px-4 py-2 hover:bg-indigo-700">
                    Guardar Aula
                </button>
                <a href="{{ route('coordinador.aulas.index') }}" class="text-gray-600 hover:underline self-center">
                    Cancelar
                </a>
            </div>

        </form>
    </div>
</body>
</html>
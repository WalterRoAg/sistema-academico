<!DOCTYPE html>
<html lang="es">
<head>
    <title>Registrar Materia</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
    <h1 class="text-2xl font-bold mb-4">Registrar Nueva Materia (CU-004)</h1>

    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-red-100 p-4 text-sm text-red-700">
            <p class="font-bold">¡Ups! Hubo algunos problemas:</p>
            <ul class="list-disc pl-5 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white p-6 rounded-xl shadow border max-w-2xl">
        <form action="{{ route('coordinador.materias.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="sigla" class="block text-sm font-medium text-gray-700">Sigla (PK)</label>
                <input type="text" id="sigla" name="sigla" value="{{ old('sigla') }}" required maxlength="20"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre de la Materia</label>
                <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" required maxlength="100"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="nivel" class="block text-sm font-medium text-gray-700">Nivel (Ej. Primer Semestre)</label>
                <input type="text" id="nivel" name="nivel" value="{{ old('nivel') }}" maxlength="50"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div class="flex gap-4 pt-4">
                <button type="submit" class="rounded-lg bg-indigo-600 text-white px-4 py-2 hover:bg-indigo-700">Guardar Materia</button>
                <a href="{{ route('coordinador.materias.index') }}" class="text-gray-600 hover:underline self-center">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>
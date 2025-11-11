<!DOCTYPE html>
<html lang="es">
<head>
    <title>Editar Grupo</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
    <h1 class="text-2xl font-bold mb-4">Editar Grupo (CU-006)</h1>

    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-red-100 p-4 text-sm text-red-700">
            <ul class="list-disc pl-5 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white p-6 rounded-xl shadow border max-w-lg">
        <form action="{{ route('coordinador.grupos.update', $grupo) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre del Grupo (ej. SA)</label>
                <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $grupo->nombre) }}" required maxlength="100"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div class="flex gap-4 pt-4">
                <button type="submit" class="rounded-lg bg-indigo-600 text-white px-4 py-2 hover:bg-indigo-700">Actualizar Grupo</button>
                <a href="{{ route('coordinador.grupos.index') }}" class="text-gray-600 hover:underline self-center">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>
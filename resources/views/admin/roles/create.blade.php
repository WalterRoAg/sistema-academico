<!DOCTYPE html>
<html lang="es">
<head>
    <title>Añadir Rol</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
    <h1 class="text-2xl font-bold mb-4">Añadir Nuevo Rol</h1>

    <div class="bg-white p-6 rounded-xl shadow border max-w-lg">
        <form action="{{ route('admin.roles.store') }}" method="POST" class="space-y-4">
            @csrf
            
            <div>
                <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre del Rol (*)</label>
                <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('nombre') border-red-500 @enderror">
                @error('nombre')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="caracteristica" class="block text-sm font-medium text-gray-700">Característica / Descripción</label>
                <input type="text" id="caracteristica" name="caracteristica" value="{{ old('caracteristica') }}"
                       placeholder="Ej: Decano, Jefe de Carrera"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('caracteristica') border-red-500 @enderror">
                @error('caracteristica')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-4 pt-4">
                <button type="submit" 
                        class="rounded-lg bg-indigo-600 text-white px-4 py-2 hover:bg-indigo-700">
                    Guardar Rol
                </button>
                <a href="{{ route('admin.roles.index') }}" class="text-gray-600 hover:underline self-center">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</body>
</html>
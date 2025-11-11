<!DOCTYPE html>
<html lang="es">
<head>
    <title>Editar Usuario</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
    <h1 class="text-2xl font-bold mb-4">Editar Usuario (CU-013)</h1>

    <div class="bg-white p-6 rounded-xl shadow border max-w-2xl">
        
        <form action="{{ route('admin.usuarios.update', $usuario) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="nombre" class="block text-sm font-medium text-gray-700">Usuario (CI)</label>
                <input type="text" id="nombre" name="nombre" 
                       value="{{ old('nombre', $usuario->nombre) }}" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('nombre') border-red-500 @enderror">
                
                @error('nombre')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="nombre_persona" class="block text-sm font-medium text-gray-700">Nombre Completo (Persona)</label>
                <input type="text" id="nombre_persona" 
                       value="{{ $usuario->persona?->nombre }}" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm bg-gray-100" readonly>
            </div>

            <div>
                <label for="correo" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                <input type="email" id="correo" name="correo" 
                       value="{{ old('correo', $usuario->correo) }}" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('correo') border-red-500 @enderror">

                @error('correo')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="rol_id" class="block text-sm font-medium text-gray-700">Rol</label>
                <select id="rol_id" name="rol_id" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('rol_id') border-red-500 @enderror">
                    
                    @foreach ($roles as $rol)
                        <option value="{{ $rol->id }}" 
                                {{ old('rol_id', $usuario->rol_id) == $rol->id ? 'selected' : '' }}>
                            {{ $rol->nombre }}
                        </option>
                    @endforeach
                </select>

                @error('rol_id')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="caracteristica" class="block text-sm font-medium text-gray-700">Característica (Cargo)</label>
                <input type="text" id="caracteristica" name="caracteristica" 
                       value="{{ old('caracteristica', $usuario->caracteristica) }}"
                       placeholder="Ej: Decano, Docente Oficial, Coordinador de Horarios"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('caracteristica') border-red-500 @enderror">
                
                @error('caracteristica')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="activo" class="block text-sm font-medium text-gray-700">Estado</label>
                <select id="activo" name="activo" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    
                    <option value="1" {{ old('activo', $usuario->activo) == 1 ? 'selected' : '' }}>Activo</option>
                    <option value="0" {{ old('activo', $usuario->activo) == 0 ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>

            <div class="flex gap-4 pt-4">
                <button type="submit" 
                        class="rounded-lg bg-indigo-600 text-white px-4 py-2 hover:bg-indigo-700">
                    Guardar Cambios
                </button>
                <a href="{{ route('admin.usuarios.index') }}" class="text-gray-600 hover:underline self-center">
                    Cancelar
                </a>
            </div>

        </form>
    </div>
</body>
</html>
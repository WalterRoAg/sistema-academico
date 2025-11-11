<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cambiar Mi Contraseña</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4 md:p-10">
    <h1 class="text-2xl font-bold mb-4">Gestionar Contraseña Propia (CU-17)</h1>

    <div class="bg-white p-6 rounded-xl shadow border max-w-lg mx-auto">

        @if (session('status'))
            <div class="mb-4 rounded-lg bg-green-100 p-4 text-sm font-medium text-green-700" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <form action="{{ route('perfil.password.update') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="password_actual" class="block text-sm font-medium text-gray-700">Contraseña Actual</label>
                <input type="password" id="password_actual" name="password_actual" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('password_actual') border-red-500 @enderror">
                
                @error('password_actual')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="nueva_password" class="block text-sm font-medium text-gray-700">Nueva Contraseña</label>
                <input type="password" id="nueva_password" name="nueva_password" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('nueva_password') border-red-500 @enderror">
                
                @error('nueva_password')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="nueva_password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Nueva Contraseña</label>
                <input type="password" id="nueva_password_confirmation" name="nueva_password_confirmation" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>

            <div class="flex items-center justify-between gap-4 pt-4">
                
                @php
                    $rolNombre = strtolower(auth()->user()->rol->nombre);
                    $dashboardRoute = match($rolNombre) {
                        'administrador' => 'admin.dashboard',
                        'coordinador' => 'coordinador.dashboard',
                        'docente' => 'docente.dashboard',
                        'autoridad' => 'autoridad.dashboard',
                        default => 'home',
                    };
                @endphp
                <a href="{{ route($dashboardRoute) }}" class="text-sm text-gray-600 hover:underline">
                    &larr; Volver al Dashboard
                </a>

                <button type="submit" 
                        class="rounded-lg bg-indigo-600 text-white px-5 py-2 hover:bg-indigo-700">
                    Actualizar Contraseña
                </button>
            </div>
        </form>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard del Docente</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4 md:p-10">
    <div class="max-w-4xl mx-auto">
        
        <div class="bg-white p-6 rounded-xl shadow border mb-6">
            <h1 class="text-2xl font-bold text-gray-800">
                ¡Bienvenido, {{ Auth::user()->persona->nombre }}!
            </h1>
            <p class="text-gray-600 mt-2">
                Este es su panel de control docente. Desde aquí podrá acceder a sus horarios y registrar su asistencia.
            </p>
        </div>

        @if (session('error'))
            <div class="mb-4 rounded-lg bg-red-100 p-4 text-sm font-medium text-red-700" role="alert">
                {{ session('error') }}
            </div>
        @endif
        
        @if (session('status'))
            <div class="mb-4 rounded-lg bg-green-100 p-4 text-sm font-medium text-green-700" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <a href="{{ route('docente.horario.index') }}" class="block p-6 bg-white rounded-xl shadow border hover:bg-gray-50 transition">
                <h2 class="text-xl font-semibold text-indigo-600">Ver Mi Horario</h2>
                <p class="text-gray-500 mt-1">
                    Consulte su carga horaria, materias, grupos y aulas asignadas para la gestión actual.
                </p>
            </a>

            <a href="{{ route('docente.asistencia.escanear') }}" class="block p-6 bg-white rounded-xl shadow border hover:bg-gray-50 transition">
                <h2 class="text-xl font-semibold text-indigo-600">Registrar Asistencia</h2>
                <p class="text-gray-500 mt-1">
                    Acceda al panel para escanear el código QR y registrar su asistencia a clases.
                </p>
            </a>
        </div>
        <div class="mt-6">
    <a href="{{ route('perfil.password.edit') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
        Cambiar mi contraseña (CU-17)
    </a>
</div>
        <div class="mt-10 text-center">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-gray-500 hover:text-red-600 hover:underline">
                    Cerrar Sesión
                </button>
            </form>
        </div>


    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Autoridad</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .dashboard-link {
            display: block;
            padding: 1rem;
            margin-top: 1rem;
            background-color: #fff;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.2s;
        }
        .dashboard-link:hover {
            background-color: #f9fafb;
            border-color: #4f46e5;
        }
        .dashboard-link h2 {
            font-size: 1.125rem;
            font-weight: 600;
            color: #4f46e5;
        }
        .dashboard-link p {
            font-size: 0.875rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }
    </style>
</head>
<body class="bg-gray-100 p-4 md:p-10">
    <div class="max-w-4xl mx-auto">
        
        <div class="bg-white p-6 rounded-xl shadow border mb-6">
            <h1 class="text-2xl font-bold text-gray-800">
                ¡Bienvenido, {{ Auth::user()->persona->nombre }}!
            </h1>
            <p class="text-gray-600 mt-2">
                Este es el panel de Autoridad. Desde aquí podrá consultar horarios y reportes.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <a href="{{ route('autoridad.horarios.index') }}" class="dashboard-link">
                <h2>Consultar Horarios (CU-07)</h2>
                <p>Ver la carga horaria completa de todos los docentes.</p>
            </a>

            <a href="{{ route('autoridad.reportes.asistencia.index') }}" class="dashboard-link">
                <h2>Generar Reportes (CU-10)</h2>
                <p>Generar reportes de asistencia de docentes (PDF/Excel).</p>
            </a>
        </div>

        <div class="mt-10 flex justify-between items-center">
            
            <a href="{{ route('perfil.password.edit') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                Cambiar mi contraseña (CU-17)
            </a>
            
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-sm text-gray-500 hover:text-red-600 hover:underline">
                    Cerrar Sesión
                </button>
            </form>
        </div>

    </div>
</body>
</html>
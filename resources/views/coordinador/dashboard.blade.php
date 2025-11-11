<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard del Coordinador</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Reutilizamos los estilos de los otros dashboards para consistencia */
        .dashboard-link {
            display: block;
            padding: 1rem;
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
            color: #4f46e5; /* Color índigo */
        }
        .dashboard-link p {
            font-size: 0.875rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }
    </style>
</head>
<body class="bg-gray-100 p-4 md:p-10">
    <div class="max-w-6xl mx-auto">
        
        <div class="bg-white p-6 rounded-xl shadow border mb-6">
            <h1 class="text-2xl font-bold text-gray-800">
                ¡Bienvenido, {{ Auth::user()->persona->nombre }}!
            </h1>
            <p class="text-gray-600 mt-2">
                Este es el panel de Coordinación Académica.
            </p>
        </div>
        
        @if (session('status'))
            <div class="mb-4 rounded-lg bg-green-100 p-4 text-sm font-medium text-green-700" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <a href="{{ route('coordinador.aulas.index') }}" class="dashboard-link">
                <h2>Gestionar Aulas</h2>
                <p>Crear, editar y listar las aulas.</p>
            </a>
            <a href="{{ route('coordinador.materias.index') }}" class="dashboard-link">
                <h2>Gestionar Materias</h2>
                <p>Crear, editar y listar las materias.</p>
            </a>
            <a href="{{ route('coordinador.grupos.index') }}" class="dashboard-link">
                <h2>Gestionar Grupos</h2>
                <p>Crear, editar y listar los grupos (ej. A, B, C).</p>
            </a>
            
            <a href="{{ route('coordinador.docentes.index') }}" class="dashboard-link">
                <h2>Gestionar Docentes</h2>
                <p>Registrar y asignar docentes a sus perfiles.</p>
            </a>

            <a href="{{ route('coordinador.grupo-materia.index') }}" class="dashboard-link">
                <h2>Apertura de Materias</h2>
                <p>Asignar materias y docentes a los grupos del período.</p>
            </a>

            <a href="{{ route('coordinador.planificacion.index') }}" class="dashboard-link">
                <h2>Planificación Horaria</h2>
                <p>Asignar horarios, aulas y docentes a las materias aperturadas.</p>
            </a>
            <a href="{{ route('coordinador.asistencia.panel') }}" class="dashboard-link">
                <h2>Panel de Asistencia (QR)</h2>
                <p>Generar códigos QR en vivo para la asistencia docente.</p>
            </a>
            <a href="{{ route('coordinador.aulas.disponibles') }}" class="dashboard-link">
                <h2>Consultar Aulas Libres (CU-18)</h2>
                <p>Ver el estado (Libre/Ocupado) de las aulas por horario.</p>
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
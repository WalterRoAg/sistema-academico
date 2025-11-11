<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Estilos consistentes para los enlaces del dashboard */
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
                Panel de Administrador. Rol: <strong>{{ Auth::user()->rol->nombre }}</strong>
            </p>
        </div>

        @if (session('status'))
            <div class="mb-4 rounded-lg bg-green-100 p-4 text-sm font-medium text-green-700" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <a href="{{ route('admin.usuarios.index') }}" class="dashboard-link">
                <h2>Gestionar Usuarios (CU-013)</h2>
                <p>Crear, editar y asignar roles a los usuarios del sistema.</p>
            </a>
            
            <a href="{{ route('admin.roles.index') }}" class="dashboard-link">
                <h2>Gestionar Roles</h2>
                <p>Definir los roles y permisos (aunque los permisos no están implementados).</p>
            </a>

            <a href="{{ route('admin.reportes.asistencia.index') }}" class="dashboard-link">
                <h2>Reportes de Asistencia (CU-10)</h2>
                <p>Generar reportes en PDF o Excel de la asistencia docente.</p>
            </a>

            <a href="{{ route('admin.importar.usuarios.create') }}" class="dashboard-link">
                <h2>Importar Usuarios (CU-14)</h2>
                <p>Crear usuarios y personas masivamente desde un archivo Excel.</p>
            </a>

            <a href="{{ route('admin.bitacora.index') }}" class="dashboard-link">
                <h2>Bitácora del Sistema (CU-16)</h2>
                <p>Auditar todas las acciones, logins e IPs del sistema.</p>
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
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Importar Lote de Usuarios</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
    <h1 class="text-2xl font-bold mb-4">Importar Lote de Usuarios (CU-14)</h1>

    <div class="bg-white p-6 rounded-xl shadow border max-w-2xl">
        
        @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-100 p-4 text-sm font-medium text-red-700">
                <p class="font-bold">Error en el formulario:</p>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('validation_errors'))
            <div class="mb-4 rounded-lg bg-red-100 p-4 text-sm font-medium text-red-700">
                <p class="font-bold">Errores encontrados en el archivo. No se importó ningún dato:</p>
                <ul class="list-disc pl-5 mt-2">
                    @foreach (session('validation_errors') as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 rounded-lg bg-red-100 p-4 text-sm font-medium text-red-700">
                <p class="font-bold">Error Inesperado:</p>
                <p>{{ session('error') }}</p>
            </div>
        @endif
        
        <form action="{{ route('admin.importar.usuarios.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label for="archivo" class="block text-sm font-medium text-gray-700">
                    Archivo (.xlsx, .csv)
                </label>
                <input type="file" id="archivo" name="archivo" required
                       class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
            </div>

            <div class="bg-blue-100 p-4 rounded-lg text-blue-800">
                <p class="font-semibold text-sm">Instrucciones:</p>
                <p class="text-xs mt-1">El archivo debe tener las siguientes columnas (encabezados):</p>
                <ul class="text-xs list-disc pl-5 mt-1">
                    <li><strong>nombre_completo</strong> (Nombre de la persona)</li>
                    <li><strong>email</strong> (Debe ser único)</li>
                    <li><strong>ci_persona</strong> (CI de la persona, debe ser único)</li>
                    <li><strong>ci_usuario</strong> (CI para el login, debe ser único)</li>
                    <li><strong>rol_id</strong> (COLOCAR UN UNICO NUMERO ejm:1=admin,2=coordinador,3=docente,4=autoridad)</li>
                    <li><strong>password_temporal</strong> (Mínimo 8 caracteres)</li>
                    <li><strong>caracteristica</strong> (descripcion de profecion o ocupacion)</li>
                </ul>
            </div>

            <div class="flex gap-4 pt-4">
                <button type="submit" 
                        class="rounded-lg bg-indigo-600 text-white px-4 py-2 hover:bg-indigo-700">
                    Iniciar Importación
                </button>
                <a href="{{ url()->previous() }}" class="text-gray-600 hover:underline self-center">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
    </div>
        <a href="{{ route('admin.dashboard') }}" class="mt-6 inline-block text-indigo-600 hover:underline">&larr; Volver al Dashboard</a>
    </div>
</body>
</html>
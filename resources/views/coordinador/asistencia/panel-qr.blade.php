<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Panel de Asistencia QR</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .qr-wrapper { display: flex; justify-content: center; align-items: center; }
    </style>
</head>
<body class="bg-gray-100 p-4 md:p-10">
    
    <div class="max-w-md mx-auto bg-white rounded-xl shadow-md p-6 border">
        
        <h1 class="text-2xl font-bold text-center text-gray-800 mb-4">Panel de Asistencia QR (CU-12)</h1>

        <div class="mb-6">
            <label for="clase_selector" class="block text-sm font-medium text-gray-700 mb-1">
                Seleccione la clase a activar:
            </label>
            <select id="clase_selector" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                <option value="" disabled selected>-- Elija una clase de hoy --</option>
                @forelse ($clasesActivas as $clase)
                    <option value="{{ $clase->id }}" @selected($tokenActivo && $tokenActivo->horario_clase_id == $clase->id)>
                        {{ $clase->horario->dia }} ({{ $clase->horario->hora_ini->format('H:i') }}) | 
                        {{ $clase->grupoMateria->materia->sigla }} - {{ $clase->docente->persona->nombre }}
                    </option>
                @empty
                    <option value="" disabled>No hay clases asignadas para hoy.</option>
                @endforelse
            </select>
        </div>
        
        <button id="btn-generar" 
                disabled
                class="w-full rounded-lg bg-indigo-600 text-white px-5 py-2 hover:bg-indigo-700 font-medium disabled:bg-indigo-300 transition">
            Generar QR y Empezar Conteo (60 seg)
        </button>
        
        <div id="qr-display" class="mt-8 p-4 border-4 border-dashed rounded-lg flex justify-center flex-col items-center min-h-[300px]">
            @if ($tokenActivo)
                <div class="qr-wrapper mb-4" id="qr-placeholder">{!! $tokenActivo->qr_code_svg !!}</div>
                <p id="token-status" class="text-green-600 font-semibold">QR ACTIVO</p>
                <p id="token-expira-at" class="text-sm text-gray-600">Expira: {{ $tokenActivo->expira_en->format('H:i:s') }}</p>
            @else
                <p id="qr-placeholder" class="text-gray-500">Seleccione una clase y genere el QR.</p>
                <p id="token-status" class="hidden"></p>
                <p id="token-expira-at" class="hidden"></p>
            @endif
        </div>
        
        <a href="{{ route('coordinador.dashboard') }}" class="mt-6 inline-block text-center w-full text-indigo-600 hover:underline">
            &larr; Volver al Dashboard
        </a>

    </div>

    <script>
        const tokenUrl = "{{ route('coordinador.asistencia.generar-token') }}";
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const claseSelector = document.getElementById('clase_selector');
        const btnGenerar = document.getElementById('btn-generar');
        const qrDisplay = document.getElementById('qr-display');
        const defaultMessage = 'Generar QR y Empezar Conteo (60 seg)';
        let timer; 

        // Habilita/deshabilita el botón
        claseSelector.addEventListener('change', () => {
            btnGenerar.disabled = !claseSelector.value;
            // Limpia el QR si se cambia la selección
            qrDisplay.innerHTML = `<p id="qr-placeholder" class="text-gray-500">Seleccione una clase y genere el QR.</p>
                                   <p id="token-status" class="hidden"></p>
                                   <p id="token-expira-at" class="hidden"></p>`;
            btnGenerar.textContent = defaultMessage;
            clearTimeout(timer); // Detiene el refresco automático
        });

        // Función que actualiza la interfaz y programa el temporizador
        function updateUI(data) {
            const nowTime = new Date();
            
            qrDisplay.innerHTML = `<div class="qr-wrapper mb-4" id="qr-placeholder">${data.qr_code_svg}</div>`; 
            qrDisplay.innerHTML += `<p id="token-status" class="text-green-600 font-semibold mt-4">QR ACTIVO</p>`;
            qrDisplay.innerHTML += `<p id="token-expira-at" class="text-sm text-gray-600">Expira: ${data.expira_en}</p>`;

            const expiraEn = data.expira_en; 
            const expiresAt = new Date(nowTime.toDateString() + ' ' + expiraEn);
            if (expiresAt.getTime() < nowTime.getTime()) {
                 expiresAt.setDate(expiresAt.getDate() + 1);
            }
            let delay = expiresAt.getTime() - nowTime.getTime() + 1000;

            btnGenerar.textContent = 'QR ACTIVO (Refrescando en 60s)';
            btnGenerar.disabled = true; 

            clearTimeout(timer);
            timer = setTimeout(fetchAndGenerateQR, delay);
        }

        // Función principal para generar el token
        async function fetchAndGenerateQR() {
            const horarioClaseId = claseSelector.value;
            if (!horarioClaseId) return; 

            qrDisplay.innerHTML = `<p class="text-yellow-600 font-medium">Generando nuevo token...</p>`;
            
            try {
                const response = await fetch(tokenUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                    body: JSON.stringify({ horario_clase_id: horarioClaseId }),
                });
                const data = await response.json(); 

                if (!response.ok || !data.success) {
                    qrDisplay.innerHTML = `<p class="text-red-600 font-medium">Error: ${data.message || 'Error'}</p>`;
                    btnGenerar.textContent = defaultMessage;
                    btnGenerar.disabled = (claseSelector.value === ""); // Deshabilitado si no hay selección
                    return;
                }
                updateUI(data);

            } catch (err) {
                qrDisplay.innerHTML = '<p class="text-red-600 font-medium">Error de conexión. Reintentando...</p>';
                setTimeout(fetchAndGenerateQR, 5000);
            }
        }

        // 1. Listener del botón
        btnGenerar.addEventListener('click', fetchAndGenerateQR);

        // 2. Lógica para iniciar el temporizador si la página se recarga con un token activo
        @if ($tokenActivo)
            document.addEventListener('DOMContentLoaded', () => {
                // Habilita el selector (si no estaba ya seleccionado)
                claseSelector.value = '{{ $tokenActivo->horario_clase_id }}';
                
                const activeData = {
                    qr_code_svg: `{!! $tokenActivo->qr_code_svg !!}`,
                    expira_en: '{{ $tokenActivo->expira_en->format("H:i:s") }}'
                };
                updateUI(activeData); 
            });
        @endif
    </script>
</body>
</html>
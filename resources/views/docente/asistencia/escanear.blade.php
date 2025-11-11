<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Escanear QR de Asistencia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-md mx-auto bg-white rounded-xl shadow-md p-6">
        <h1 class="text-2xl font-bold text-center text-gray-800 mb-4">Registrar Asistencia (CU-09)</h1>

        <div id="reader" class="w-full border-4 border-dashed rounded-lg" style="width: 100%;"></div>

        <div id="status-message" class="mt-4 p-4 rounded-lg text-center font-medium">
            <p class="text-gray-600">Apunte la cámara al código QR del kiosco.</p>
        </div>

        <a href="{{ route('docente.dashboard') }}" class="mt-4 inline-block text-center w-full text-indigo-600 hover:underline">
            &larr; Volver al Dashboard
        </a>
    </div>

    <script type="text/javascript">
        // Obtenemos el token CSRF de la etiqueta meta
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const statusElement = document.getElementById('status-message');
        let html5QrCode; // Variable para el escáner

        // Función que se llama cuando el QR se escanea exitosamente
        async function onScanSuccess(decodedText, decodedResult) {
            
            // Pausar el escáner para evitar múltiples envíos
            try {
                if (html5QrCode && html5QrCode.getState() === 2) { // 2 = SCANNING
                     await html5QrCode.pause();
                }
            } catch(e) { console.warn("Error al pausar:", e); }

            statusElement.className = 'mt-4 p-4 rounded-lg text-center font-medium bg-yellow-100 text-yellow-800';
            statusElement.innerHTML = '<p>Token detectado. Verificando...</p>';

            // --- (Punto 4: Enviar token al servidor) ---
            try {
                const response = await fetch("{{ route('docente.asistencia.registrar') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken 
                    },
                    body: JSON.stringify({ token: decodedText }) // decodedText es el token
                });

                const data = await response.json();

                if (response.ok) {
                    // --- (Punto 10: Mostrar Éxito) ---
                    statusElement.className = 'mt-4 p-4 rounded-lg text-center font-medium bg-green-100 text-green-800';
                    statusElement.innerHTML = `<p>${data.message}</p>`;
                    // Detener la cámara permanentemente
                    if (html5QrCode) {
                        html5QrCode.stop().catch(err => console.error("Error al detener:", err));
                    }
                } else {
                    // --- (Punto 10: Mostrar Error) ---
                    // Muestra el mensaje de error del servidor (ej. "Fuera de rango", "Día incorrecto")
                    statusElement.className = 'mt-4 p-4 rounded-lg text-center font-medium bg-red-100 text-red-800';
                    statusElement.innerHTML = `<p>${data.message}</p>`;
                    
                    // Reanudar el escáner después de 3 segundos para un nuevo intento
                    setTimeout(async () => {
                         try {
                            if (html5QrCode && html5QrCode.getState() === 3) { // 3 = PAUSED
                                await html5QrCode.resume();
                                statusElement.className = 'mt-4 p-4 rounded-lg text-center font-medium bg-gray-100 text-gray-700';
                                statusElement.innerHTML = '<p>Intente escanear de nuevo.</p>';
                            }
                         } catch(e) { console.warn("Error al reanudar:", e); }
                    }, 3000);
                }
            
            } catch (error) {
                // Error de red
                console.error("Error en fetch:", error);
                statusElement.className = 'mt-4 p-4 rounded-lg text-center font-medium bg-red-100 text-red-800';
                statusElement.innerHTML = '<p>Error de conexión con el servidor.</p>';
                if (html5QrCode && html5QrCode.getState() === 3) await html5QrCode.resume();
            }
        }

        function onScanFailure(error) {
            // No hacer nada, solo sigue escaneando
        }

        // --- (Punto 2: Activar la cámara) ---
        document.addEventListener("DOMContentLoaded", () => {
            html5QrCode = new Html5Qrcode("reader"); // "reader" es el ID del div
            html5QrCode.start(
                { facingMode: "environment" }, // Pedir la cámara trasera
                {
                    fps: 10,    // Frames por segundo
                    qrbox: { width: 250, height: 250 } // Tamaño del visor
                },
                onScanSuccess, // Función de éxito
                onScanFailure  // Función de fallo (ignorada)
            ).catch(err => {
                statusElement.innerHTML = `<p class="text-red-600">Error al iniciar la cámara: ${err}</p>`;
            });
        });
    </script>
</body>
</html>
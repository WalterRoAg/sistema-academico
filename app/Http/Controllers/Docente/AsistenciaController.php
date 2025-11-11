<?php

namespace App\Http\Controllers\Docente; // <-- 1. Namespace de Docente

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\AsistenciaToken;
use App\Models\HorarioClase;
use App\Models\Asistencia;
use App\Models\Horario;

class AsistenciaController extends Controller // <-- 2. Clase de Docente
{
    /**
     * (Punto 1 y 2) Muestra la vista con la cámara para escanear el QR.
     */
    public function escanear()
    {
        // Asegúrate que tu vista se llame 'escanear.blade.php'
        return view('docente.asistencia.escanear');
    }

    /**
     * (Puntos 4-10) Valida el token QR y registra la asistencia.
     */
    public function registrar(Request $request)
    {
        // (Punto 4: Recibir el token)
        $datos = $request->validate(['token' => 'required|string']);
        
        $scannedToken = $datos['token'];
        $docentePersonaId = Auth::user()->persona_id;
        $now = Carbon::now(); 

        try {
            // --- (Punto 5: Validar Token) ---
            $asistenciaToken = AsistenciaToken::where('token', $scannedToken)->first();

            if (!$asistenciaToken) {
                return $this->errorResponse('Token inválido o no encontrado.', 404);
            }
            if ($asistenciaToken->utilizado) {
                return $this->errorResponse('Este código QR ya fue utilizado.', 409);
            }
            if ($now->gt($asistenciaToken->expira_en)) {
                $asistenciaToken->update(['utilizado' => true]); 
                return $this->errorResponse('El código QR ha expirado (ventana de 60s).', 410);
            }

            // --- (Punto 6: Marcar token como "utilizado") ---
            $asistenciaToken->update(['utilizado' => true]);

            $horarioClaseId = $asistenciaToken->horario_clase_id;

            // --- (Punto 7: Validar Clase y Regla de Tiempo) ---
            
            // Usamos Eloquent (NO usamos la vista 'vw_horario_detalle')
            $claseActiva = HorarioClase::with('horario')
                ->where('id', $horarioClaseId)
                ->first();

            if (!$claseActiva) {
                 return $this->errorResponse('Error: El horario asociado a este QR fue eliminado.', 404);
            }
            if ($claseActiva->docente_persona_id != $docentePersonaId) {
                return $this->errorResponse('Asistencia rechazada: El QR no corresponde a sus clases.', 403);
            }

            $horario = $claseActiva->horario;
            if (!$horario) {
                return $this->errorResponse('Error de datos: La clase no tiene un bloque horario.', 500);
            }

            // 7c. ¿Es el día correcto?
            $diaProgramado = $horario->dia; 
            $diaActual = $now->isoFormat('dddd');
            
            if (strcasecmp($diaProgramado, $diaActual) !== 0) {
                return $this->errorResponse("Día incorrecto. Hoy es {$diaActual}, la clase es el {$diaProgramado}.", 400);
            }

            // 7d. ¿Está en la ventana de $\pm$15 minutos?
            $horaInicioClase = $horario->hora_ini; 
            $horaInicioProgramadaHoy = $now->copy()->setTimeFrom($horaInicioClase);
            $inicioTolerancia = $horaInicioProgramadaHoy->copy()->subMinutes(15);
            $finTolerancia = $horaInicioProgramadaHoy->copy()->addMinutes(15);

            if (!$now->isBetween($inicioTolerancia, $finTolerancia)) {
                return $this->errorResponse('Estás fuera del rango de $\pm$15 minutos. Rango: ' . 
                                 $inicioTolerancia->format('H:i') . ' - ' . 
                                 $finTolerancia->format('H:i'), 400);
            }
            
            // 7e. ¿Ya marcó asistencia hoy?
            $yaAsistio = Asistencia::where('horario_clase_id', $horarioClaseId)
                           ->whereDate('fecha_hora', $now->toDateString())
                           ->exists();
            if ($yaAsistio) {
                return $this->errorResponse('Error: Usted ya registró asistencia para esta clase el día de hoy.', 409);
            }

            // --- (Punto 8: Guardar la Asistencia) ---
            Asistencia::create([
                'horario_clase_id'   => $horarioClaseId,
                'fecha_hora'         => $now,
                'estado'             => 'Presente', 
                'observacion'        => 'Marcada por QR',
                'docente_id'         => $docentePersonaId, // Asegúrate que tu tabla Asistencia tenga esta columna
            ]);

            // --- (Punto 9: Bitácora) ---
            activity()
                ->causedBy(Auth::user())
                ->performedOn(Asistencia::latest('id')->first())
                ->log('Asistencia Registrada por QR');

            // --- (Punto 10: Mensaje de Éxito) ---
            return response()->json([
                'success' => true,
                'message' => '¡Asistencia registrada exitosamente! Hora: ' . $now->format('H:i:s')
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Error al registrar asistencia (Docente): ' . $e->getMessage());
            return $this->errorResponse('Error interno del servidor: ' . $e->getMessage(), 500);
        }
    }

    private function errorResponse(string $message, int $statusCode)
    {
        return response()->json(['success' => false, 'message' => $message], $statusCode);
    }
}
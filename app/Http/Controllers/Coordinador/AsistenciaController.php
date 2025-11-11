<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // <-- 1. IMPORTAMOS EL HELPER DE TEXTO
use Carbon\Carbon;
use App\Models\AsistenciaToken;
use App\Models\HorarioClase;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AsistenciaController extends Controller
{
    /**
     * Muestra el panel QR (CU-12) con el SELECTOR de clases.
     */
    public function showPanel()
    {
        $now = Carbon::now();
        
        // --- 👇 ¡AQUÍ ESTÁ LA CORRECCIÓN! 👇 ---
        // Forzamos la primera letra a Mayúscula para que coincida con la BD
        // (ej. 'martes' -> 'Martes' o 'miércoles' -> 'Miércoles')
        $diaActual = Str::ucfirst($now->isoFormat('dddd'));

        // 1. Obtener TODAS las clases de HOY para el selector
        $clasesActivas = HorarioClase::with([
            'horario', 'docente.persona', 'grupoMateria.materia', 'grupoMateria.grupo'
        ])
        // Usamos una comparación exacta (=) en lugar de LIKE
        ->whereHas('horario', fn($q) => $q->where('dia', $diaActual)) 
        ->get()
        ->sortBy(fn($clase) => $clase->horario->hora_ini); 

        // 2. Buscar si ya hay un token activo
        $tokenActivo = AsistenciaToken::where('utilizado', false)
            ->where('expira_en', '>', now())
            ->latest('expira_en')
            ->first();
        
        // 3. Si hay un token, generar su SVG para mostrarlo
        if ($tokenActivo) {
            $tokenActivo->qr_code_svg = $this->generarQrCodeBase64($tokenActivo->token);
        }

        // 4. Pasamos las clases (para el selector) y el token (si existe)
        return view('coordinador.asistencia.panel-qr', compact('clasesActivas', 'tokenActivo'));
    }

    /**
     * Genera un nuevo token QR para la clase específica que el Coordinador seleccionó.
     */
    public function generarToken(Request $request)
    {
        $request->validate(['horario_clase_id' => 'required|exists:horario_clase,id']);

        $horarioClaseId = $request->horario_clase_id;
        $token = $this->forceNewToken($horarioClaseId);
        
        return response()->json([
            'success' => true,
            'token_value' => $token->token,
            'qr_code_svg' => $this->generarQrCodeBase64($token->token),
            'expira_en' => $token->expira_en->format('H:i:s'),
            'horario_clase_id' => $horarioClaseId,
        ]);
    }
    
    /**
     * Lógica que SIEMPRE crea un nuevo token de 60s.
     */
    private function forceNewToken(int $horarioClaseId)
    {
         $expiracion = now()->addSeconds(60);
         
         AsistenciaToken::where('horario_clase_id', $horarioClaseId)
                         ->where('utilizado', false)
                         ->update(['utilizado' => true]); 

         $token = AsistenciaToken::create([
             'token' => Str::random(60),
             'expira_en' => $expiracion,
             'utilizado' => false,
             'horario_clase_id' => $horarioClaseId,
         ]);
         
         return $token;
    }
    
    /**
     * Helper para generar el QR como SVG.
     */
    private function generarQrCodeBase64(string $data): string
    {
        return QrCode::size(250)->margin(2)->generate($data);
    }
}
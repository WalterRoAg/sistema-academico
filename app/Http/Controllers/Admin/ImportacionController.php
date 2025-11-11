<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsuariosImport; // <-- Importamos nuestra clase
use Maatwebsite\Excel\Validators\ValidationException; // <-- Para capturar errores

class ImportacionController extends Controller
{
    /**
     * Muestra la vista con el formulario para subir el archivo.
     */
    public function create()
    {
        return view('admin.importar.create');
    }

    /**
     * Procesa el archivo Excel/CSV subido.
     */
    public function store(Request $request)
    {
        // 1. Validar el archivo en sí
        $request->validate([
            'archivo' => 'required|mimes:xlsx,csv,xls'
        ]);

        try {
            // 2. Obtener el archivo
            $file = $request->file('archivo');

            // 3. Procesar la importación
            Excel::import(new UsuariosImport, $file);
            
            // 4. Si todo va bien, redirigir con éxito
            return redirect()->route('admin.usuarios.index') // O al dashboard
                         ->with('status', 'Importación de usuarios completada exitosamente.');

        } catch (ValidationException $e) {
            // 5. Capturar errores de validación (de las reglas en UsuariosImport.php)
            $failures = $e->failures(); // Colección de errores
            
            $errores = [];
            foreach ($failures as $failure) {
                $errores[] = "Fila " . $failure->row() . ": " . 
                             $failure->errors()[0] . " (Columna: " . 
                             $failure->attribute() . ")";
            }

            // Redirigir de vuelta con los errores
            return redirect()->back()->with('validation_errors', $errores);
        } catch (\Exception $e) {
            // 6. Capturar cualquier otro error (ej. error de BD)
            return redirect()->back()->with('error', 'Error inesperado: ' . $e->getMessage());
        }
    }
}
<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Persona;
use App\Models\Docente;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows; // <-- ¡NUEVA IMPORTACIÓN!

class UsuariosImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows // <-- ¡AÑADIR A LA FIRMA!
{
    /**
    * @param array $row
    * @return \Illuminate\Database\Eloquent\Model|null
    */
   public function model(array $row)
    {
        // 1. CREAR LA PERSONA (ESTO DEBE IR PRIMERO)
        // Aquí se define la variable $persona
        $persona = Persona::create([
            'nombre' => $row['nombre_completo'],
            'carnet' => $row['ci_persona'], 
        ]);

        
        // 2. VERIFICAR EL ROL Y CREAR EL DOCENTE (SI APLICA)
        
        // Viendo tu archivo Excel (lote.xlsx), parece que el rol_id de docente es el 3.
        // ¡Por favor, confírmalo en tu tabla 'rol' en la base de datos!
        
        $ID_ROL_DOCENTE = 3; // <-- ¡OJO! Asumo '3' por tu Excel. Cámbialo si es otro ID.

        if ($row['rol_id'] == $ID_ROL_DOCENTE) {
            
            // Ahora $persona SÍ existe y podemos usar su ID
            Docente::create([
                'persona_id' => $persona->id,
            ]);
        }

        
        // 3. CREAR EL USUARIO (AL FINAL)
        // El importador se encargará de guardarlo en la BD.
        return new User([
            'nombre'         => $row['ci_usuario'], 
            'correo'         => $row['email'],
            'persona_id'     => $persona->id, // Aquí también usamos $persona->id
            'rol_id'         => $row['rol_id'],
            'activo'         => 1, 
            'password'       => $row['password_temporal'],
            'caracteristica' => $row['caracteristica'] ?? null, 
        ]);
    }

    /**
     * Prepara los datos de la fila ANTES de la validación.
     */
    public function prepareForValidation($data, $index)
    {
        // Forzamos la conversión a string
        $data['ci_persona'] = (string) $data['ci_persona'];
        $data['ci_usuario'] = (string) $data['ci_usuario'];
        $data['password_temporal'] = (string) $data['password_temporal'];

        return $data;
    }

    /**
     * Define las reglas de validación para cada fila.
     */
    public function rules(): array
    {
        return [
            'nombre_completo' => 'required|string|max:255',
            'email' => 'required|email|unique:users,correo',
            'ci_persona' => 'required|string|unique:persona,carnet',
            'ci_usuario' => 'required|string|unique:users,nombre',
            'rol_id' => 'required|integer|exists:rol,id',
            'password_temporal' => 'required|string|min:8',
            'caracteristica' => 'nullable|string|max:255',
        ];
    }
    
    // ... (customValidationMessages)

    public function customValidationMessages()
    {
        return [
            'ci_persona.unique' => 'El CI/Carnet de la persona ya existe.',
            'ci_usuario.unique' => 'El CI de usuario (login) ya existe.',
            'email.unique' => 'El correo electrónico ya existe.',
            'rol_id.exists' => 'El rol_id no es válido.',
        ];
    }
}
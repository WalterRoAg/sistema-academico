<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HorarioClase extends Model
{
    use HasFactory;

    protected $table = 'horario_clase';
    public $timestamps = false; // Asumiendo que no usas created_at/updated_at

    // Campos permitidos para asignación masiva
    protected $fillable = [
        'horario_id',
        'aula_id',
        'grupo_materia_id', // Si tu tabla usa este campo único de GrupoMateria
        'docente_persona_id',
        'periodo_id',
    ];

    // Definir relaciones necesarias para la vista y la lógica
    public function horario()
    {
        return $this->belongsTo(Horario::class, 'horario_id');
    }

    public function aula()
    {
        return $this->belongsTo(Aula::class, 'aula_id');
    }

    public function grupoMateria()
    {
        return $this->belongsTo(GrupoMateria::class, 'grupo_materia_id');
    }

    public function docente()
    {
        // La clave foránea es docente_persona_id y la clave local es persona_id
        return $this->belongsTo(Docente::class, 'docente_persona_id', 'persona_id');
    }
    public function docentePersona()
    {
        // Asume que la llave 'docente_persona_id' coincide con 'personas.id'
        return $this->belongsTo(Persona::class, 'docente_persona_id');
    }   
}
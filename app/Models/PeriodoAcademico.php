<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PeriodoAcademico extends Model
{
    use HasFactory;

    /**
     * La tabla asociada con el modelo.
     * @var string
     */
    protected $table = 'periodo_academico';

    /**
     * Indica si el modelo debe tener timestamps (created_at y updated_at).
     * @var bool
     */
    public $timestamps = false;

    /**
     * Los atributos que se pueden asignar masivamente.
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'fecha_inicio',
        'fecha_fin',
        'activo',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     * @var array
     */
    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'activo' => 'boolean',
    ];

    
    // --- RELACIONES ---

    /**
     * Relación: Un Periodo Académico tiene muchas Clases (1 a N)
     */
    public function horarioClases(): HasMany
    {
        return $this->hasMany(HorarioClase::class, 'periodo_id');
    }

    /**
     * Relación: Un Periodo Académico tiene muchas Materias-Grupos (1 a N)
     */
    public function grupoMaterias(): HasMany
    {
        return $this->hasMany(GrupoMateria::class, 'periodo_id');
    }
}
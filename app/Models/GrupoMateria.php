<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupoMateria extends Model
{
    use HasFactory;

    /**
     * El nombre de la tabla asociada con el modelo.
     *
     * @var string
     */
    protected $table = 'grupo_materia';

    /**
     * Indica si el modelo debe tener timestamps.
     *
     * @var bool
     */
    public $timestamps = false; // <-- ¡ESTA ES LA CORRECCIÓN PARA EL SEEDER!

    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array
     */
    protected $fillable = [
        'grupo_id',
        'materia_sigla',
        'periodo_id',
        'activo',
    ];

    // --- ¡RELACIONES INVERSAS AÑADIDAS! ---

    /**
     * Obtiene el grupo al que pertenece.
     */
    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'grupo_id');
    }

    /**
     * Obtiene la materia a la que pertenece.
     */
    public function materia()
    {
        // belongsTo(Modelo, 'foreign_key', 'owner_key')
        return $this->belongsTo(Materia::class, 'materia_sigla', 'sigla');
    }

    /**
     * Obtiene el periodo al que pertenece.
     */
    public function periodo()
    {
        return $this->belongsTo(PeriodoAcademico::class, 'periodo_id');
    }
}
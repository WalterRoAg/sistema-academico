<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Aula extends Model
{
    use HasFactory;

    /**
     * La tabla asociada con el modelo.
     * @var string
     */
    protected $table = 'aula';

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
        'numero',
        'piso',
        'capacidad',
        'activo',
    ];

    
    // --- RELACIONES ---

    /**
     * Relación: Un Aula se usa en muchas Clases (1 a N)
     */
    public function horarioClases(): HasMany
    {
        return $this->hasMany(HorarioClase::class, 'aula_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AsistenciaToken extends Model
{
    use HasFactory;

    /**
     * El nombre de la tabla asociada con el modelo.
     * @var string
     */
    protected $table = 'asistencia_tokens';

    /**
     * Indica si el modelo debe tener timestamps.
     * @var bool
     */
    public $timestamps = false;

    /**
     * Los atributos que se pueden asignar masivamente.
     * @var array
     */
    protected $fillable = [
        'token',
        'expira_en',
        'utilizado',
        'horario_clase_id', // Clave para la relación con la clase
    ];

    /**
     * Los atributos que deben ser casteados.
     * @var array
     */
    protected $casts = [
        'expira_en' => 'datetime',
        'utilizado' => 'boolean',
    ];

    // --- RELACIONES ---

    /**
     * Un token pertenece a una asignación de HorarioClase (la clase programada).
     */
    public function horarioClase(): BelongsTo
    {
        return $this->belongsTo(HorarioClase::class, 'horario_clase_id');
    }
}
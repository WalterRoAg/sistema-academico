<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    use HasFactory;

    protected $table = 'asistencia';

    /**
     * Indica si el modelo debe tener timestamps.
     * Solo usamos 'created_at', que lo manejamos con 'fecha_hora'.
     */
    public $timestamps = false;

    /**
     * El nombre del atributo "creado en".
     * Redefinimos esto para que Laravel use nuestra columna.
     */
    const CREATED_AT = 'fecha_hora';

    protected $fillable = [
        'fecha_hora',
        'horario_clase_id',
        'estado',
        'observacion',
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
    ];

    public function horarioClase()
    {
        return $this->belongsTo(HorarioClase::class, 'horario_clase_id');
    }
}
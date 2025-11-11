<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;

    // --- ¡AÑADE ESTA LÍNEA! ---
    /**
     * El nombre de la tabla asociada con el modelo.
     *
     * @var string
     */
    protected $table = 'horario'; // <-- ESTA ES LA CORRECCIÓN

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'hora_ini' => 'datetime', // Trata 'hora_ini' como un objeto Carbon
        'hora_fin' => 'datetime', // Trata 'hora_fin' como un objeto Carbon
    ];

    /**
     * No necesitamos timestamps (created_at/updated_at) para esta tabla.
     */
    public $timestamps = false;
    
    /**
     * Atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'dia',
        'hora_ini',
        'hora_fin',
    ];
}
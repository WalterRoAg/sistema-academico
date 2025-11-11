<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bitacora extends Model
{
    use HasFactory;

    /**
     * La tabla asociada con el modelo.
     * @var string
     */
    protected $table = 'bitacora';

    /**
     * Indica si el modelo debe tener timestamps (created_at y updated_at).
     * @var bool
     */
    public $timestamps = false; // Ya tenemos nuestra propia columna 'fecha_hora'

    /**
     * Define el nombre de la columna 'created_at'.
     * @var string
     */
    const CREATED_AT = 'fecha_hora'; // Opcional: Le decimos a Laravel que use 'fecha_hora' como 'created_at'

    /**
     * Define el nombre de la columna 'updated_at'.
     * @var null
     */
    const UPDATED_AT = null; // No usamos 'updated_at'

    /**
     * Los atributos que se pueden asignar masivamente.
     * @var array<int, string>
     */
    protected $fillable = [
        'usuario_id',
        'accion',
        'entidad',
        'entidad_id',
        'descripcion',
        'ip_address',
        'fecha_hora',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     * @var array
     */
    protected $casts = [
        'fecha_hora' => 'datetime',
    ];


    // --- RELACIONES ---

    /**
     * Relación: Un registro de Bitácora pertenece a un Usuario (Inversa N a 1)
     */
    public function user(): BelongsTo
    {
        // Nota: Usamos 'User::class' (el modelo de Laravel) no 'Usuario::class'
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
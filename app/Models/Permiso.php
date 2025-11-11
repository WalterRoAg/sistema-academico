<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permiso extends Model
{
    use HasFactory;

    /**
     * La tabla asociada con el modelo.
     * @var string
     */
    protected $table = 'permiso';

    /**
     * Indica si el modelo debe tener timestamps (created_at y updated_at).
     * @var bool
     */
    public $timestamps = false; // No le pusimos timestamps en el DDL

    /**
     * Los atributos que se pueden asignar masivamente.
     * @var array<int, string>
     */
    protected $fillable = ['nombre'];

    
    // --- RELACIONES ---

    /**
     * Relación: Un Permiso pertenece a muchos Roles (N a N)
     * Tabla pivote: 'rol_permiso'
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Rol::class, 'rol_permiso', 'permiso_id', 'rol_id')
                    ->withPivot('activo'); // Para poder leer el campo 'activo' de la tabla pivote
    }
}
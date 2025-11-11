<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Rol extends Model
{
    use HasFactory;

    /**
     * La tabla asociada con el modelo.
     * @var string
     */
    protected $table = 'rol';

    /**
     * Indica si el modelo debe tener timestamps (created_at y updated_at).
     * @var bool
     */
    public $timestamps = false; // No le pusimos timestamps en el DDL

    /**
     * Los atributos que se pueden asignar masivamente.
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'caracteristica' 
    ];
    
    
    // --- RELACIONES ---

    /**
     * Relación: Un Rol tiene muchos Usuarios (1 a N)
     * El nombre de la llave foránea en la tabla 'users' es 'rol_id'
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'rol_id');
    }

    /**
     * Relación: Un Rol tiene y pertenece a muchos Permisos (N a N)
     * Tabla pivote: 'rol_permiso'
     */
    public function permisos(): BelongsToMany
    {
        return $this->belongsToMany(Permiso::class, 'rol_permiso', 'rol_id', 'permiso_id')
                    ->withPivot('activo'); // Para poder leer el campo 'activo' de la tabla pivote
    }
}
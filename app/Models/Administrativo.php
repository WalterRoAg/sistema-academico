<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Administrativo extends Model
{
    use HasFactory;

    /**
     * La tabla asociada con el modelo.
     * @var string
     */
    protected $table = 'administrativo';

    /**
     * La llave primaria asociada con la tabla.
     * @var string
     */
    protected $primaryKey = 'persona_id'; // Le decimos a Laravel que la PK no es 'id'

    /**
     * Indica si la llave primaria es autoincremental.
     * @var bool
     */
    public $incrementing = false; // Le decimos que nuestra PK no es autoincremental

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
        'persona_id',
        'cargo',
    ];

    
    // --- RELACIONES ---

    /**
     * Relación: Un Administrativo ES una Persona (Inversa de Herencia 1 a 1)
     */
    public function persona(): BelongsTo
    {
        // Un administrativo pertenece a una persona, usando 'persona_id' como FK
        return $this->belongsTo(Persona::class, 'persona_id');
    }
}
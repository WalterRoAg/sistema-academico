<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Profesion extends Model
{
    use HasFactory;

    /**
     * La tabla asociada con el modelo.
     * @var string
     */
    protected $table = 'profesion';

    /**
     * Indica si el modelo debe tener timestamps (created_at y updated_at).
     * @var bool
     */
    public $timestamps = false;

    /**
     * Los atributos que se pueden asignar masivamente.
     * @var array<int, string>
     */
    protected $fillable = ['nombre'];


    // --- RELACIONES ---

    /**
     * Relación: Una Profesión la tienen muchas Personas (N a N)
     */
    public function personas(): BelongsToMany
    {
        return $this->belongsToMany(Persona::class, 'profesion_persona', 'profesion_id', 'persona_id')
                    ->withPivot('nivel'); // Para leer el campo 'nivel'
    }
}
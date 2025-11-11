<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Persona extends Model
{
    use HasFactory;

    protected $table = 'persona';
    public $timestamps = false; 

    /**
     * Los atributos que se pueden asignar masivamente.
     * (VOLVER A LA VERSIÓN ORIGINAL)
     */
    protected $fillable = [
        'carnet', 
        'nombre', 
        'telefono'
        // <-- Asegúrate de que 'email' NO esté aquí
    ];

    // --- RELACIONES ---

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'persona_id');
    }

    public function docente(): HasOne
    {
        return $this->hasOne(Docente::class, 'persona_id');
    }

    /**
     * Relación: Una Persona es un Administrativo (1 a 1)
     */
    public function administrativo(): HasOne
    {
        return $this->hasOne(Administrativo::class, 'persona_id');
    }

    /**
     * Relación: Una Persona tiene muchas Profesiones (N a N)
     */
    public function profesiones(): BelongsToMany
    {
        return $this->belongsToMany(Profesion::class, 'profesion_persona', 'persona_id', 'profesion_id')
                    ->withPivot('nivel'); // Para leer el campo 'nivel'
    }
}
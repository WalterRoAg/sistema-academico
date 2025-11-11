<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    use HasFactory;

    protected $table = 'grupo';
    public $timestamps = false;
    
    /**
     * Los atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'nombre',
    ];

    // --- ¡RELACIÓN AÑADIDA! ---
    /**
     * Define la relación: Un Grupo puede estar en muchos GrupoMateria.
     */
    public function gruposMateria()
    {
        // hasMany(Modelo, 'foreign_key', 'local_key')
        return $this->hasMany(GrupoMateria::class, 'grupo_id', 'id');
    }
}
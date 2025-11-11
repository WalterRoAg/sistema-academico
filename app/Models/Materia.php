<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    use HasFactory;

    protected $table = 'materia';
    public $timestamps = false;

    // --- ¡CORRECCIONES IMPORTANTES! ---
    /**
     * La clave primaria del modelo.
     *
     * @var string
     */
    protected $primaryKey = 'sigla'; // Le decimos a Laravel que la PK es 'sigla'

    /**
     * Indica si la ID es autoincremental.
     *
     * @var bool
     */
    public $incrementing = false; // La 'sigla' no es autoincremental

    /**
     * El tipo de la clave primaria.
     *
     * @var string
     */
    protected $keyType = 'string'; // La 'sigla' es un string
    // --- FIN DE CORRECCIONES ---


    /**
     * Los atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'sigla',
        'nombre',
        'nivel',
    ];

    // --- ¡RELACIÓN AÑADIDA! ---
    /**
     * Define la relación: Una Materia puede estar en muchos GrupoMateria.
     */
    public function gruposMateria()
    {
        // hasMany(Modelo, 'foreign_key', 'local_key')
        return $this->hasMany(GrupoMateria::class, 'materia_sigla', 'sigla');
    }
}
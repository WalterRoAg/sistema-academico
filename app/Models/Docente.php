<?php

namespace App\Models; // <-- NAMESPACE CORRECTO PARA MODELOS

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

// Asumimos que también tiene el trait de bitácora
use Spatie\Activitylog\Traits\LogsActivity; 
use Spatie\Activitylog\LogOptions;


class Docente extends Model
{
    use HasFactory, LogsActivity;
    
    // Asumo que tu tabla es 'docente'
    protected $table = 'docente';
    public $timestamps = false; 

    // Aquí debes poner las claves foráneas que usa tu tabla 'docente'
    protected $fillable = [
        'persona_id', 
        'codigo_docente', // Si tienes esta columna
        'tipo_contrato', // Si tienes esta columna
    ];

    // --- RELACIONES CLAVE ---
    
    /**
     * Un Docente pertenece a una Persona (1:1).
     */
    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }
    
    /**
     * Un Docente también está en la tabla 'users' para el login (1:1).
     * Nota: Esto asume que tienes una relación User en Docente, 
     * o que la Persona es el intermediario.
     */
    public function user(): HasOne
    {
         // Asumo que tu Docente se vincula a User a través de Persona
         return $this->hasOneThrough(User::class, Persona::class, 'id', 'persona_id', 'persona_id', 'id');
    }

    // --- BITÁCORA ---
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable() 
            ->setDescriptionForEvent(fn(string $eventName) => "Se ha {$this->traducirEvento($eventName)} un docente");
    }
    private function traducirEvento(string $eventName): string
    {
        return match ($eventName) {
            'created' => 'creado',
            'updated' => 'actualizado',
            'deleted' => 'eliminado',
            default => $eventName,
        };
    }
    /**
     * La llave primaria asociada con la tabla.
     *
     * @var string
     */
    protected $primaryKey = 'persona_id'; // <-- ¡ESTA ES LA LÍNEA CLAVE!

    /**
     * Indica si la llave primaria es autoincremental.
     * (Probablemente 'persona_id' no es autoincremental, así que pon 'false').
     *
     * @var bool
     */
    public $incrementing = false; //
}
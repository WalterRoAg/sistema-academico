<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Hash;
use Spatie\Activitylog\Traits\LogsActivity; // <-- Bitácora
use Spatie\Activitylog\LogOptions;          // <-- Bitácora

class User extends Authenticatable
{
    // Añadimos el Trait de la bitácora
    use HasFactory, Notifiable, LogsActivity;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'nombre',
        'correo',
        'persona_id', 
        'rol_id',     
        'activo',     
        'password',
        'caracteristica' // Para el CU-17
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'contrasena',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'contrasena' => 'hashed', 
        ];
    }

    // --- 👇 ¡AQUÍ ESTÁ LA CORRECCIÓN DE LA BITÁCORA! 👇 ---
    /**
     * Define qué se debe registrar en la bitácora.
     * (VERSIÓN CORREGIDA)
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            // Loguea los atributos que cambiaron
            ->logFillable() 
            // ¡LA LÍNEA ->causedBy(...) SE ELIMINÓ!
            // El paquete detecta al usuario logueado automáticamente.
            
            // Escribe un mensaje descriptivo
            ->setDescriptionForEvent(fn(string $eventName) => "Se ha {$this->traducirEvento($eventName)} un usuario");
    }

    /**
     * (Helper para la bitácora) Traduce 'created' a 'creado'
     */
    private function traducirEvento(string $eventName): string
    {
        return match ($eventName) {
            'created' => 'creado',
            'updated' => 'actualizado',
            'deleted' => 'eliminado',
            default => $eventName,
        };
    }

    // --- LÓGICA DE CONTRASEÑA (CU-17) ---

    /**
     * Mutator para hashear la contraseña y guardarla en la columna 'contrasena'.
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['contrasena'] = Hash::make($value);
    }

    /**
     * Le dice a Laravel dónde está la contraseña para el login.
     */
    public function getAuthPassword()
    {
        return $this->contrasena; // Nombre de tu columna
    }

    // --- RELACIONES ---

    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class);
    }

    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class);
    }
}
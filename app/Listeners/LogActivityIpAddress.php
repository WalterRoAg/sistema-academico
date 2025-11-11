<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Spatie\Activitylog\Events\ActivityWasLogged; // <-- ¡Importante!

class LogActivityIpAddress
{
    /**
     * Handle the event.
     * * Esta función se ejecuta automáticamente CADA VEZ
     * que se guarda un log en la bitácora.
     */
    public function handle(ActivityWasLogged $event): void
    {
        // 'request()->ip()' obtiene la IP del visitante
        $ip = request()->ip();

        // Añadimos la IP a la columna 'properties' (que es un JSON)
        $event->activity->properties = $event->activity->properties
                       ->put('ip_address', $ip);
    }
}
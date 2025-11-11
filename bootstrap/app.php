<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Tu alias (que ya estaba y está perfecto)
        $middleware->alias([
            'rol' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
        
        // ¡AÑADE ESTA LÍNEA!
        $middleware->trustProxies(at: '*'); 
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

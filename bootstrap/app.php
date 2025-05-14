<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\TrackUserActivity;
use App\Http\Middleware\CheckUserActive;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
    // Alias existentes
    $middleware->alias([
        'modulo' => \App\Http\Middleware\ModuloAccess::class,
    ]);
    
    // Añadir middleware de seguimiento de actividad
    $middleware->web(append: [
        \App\Http\Middleware\TrackUserActivity::class,
    ]);
})
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
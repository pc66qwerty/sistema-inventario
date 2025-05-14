<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TrackUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Continuar con la solicitud
        $response = $next($request);

        // Si el usuario está autenticado, actualizar la marca de tiempo de última actividad
        if (Auth::check() && method_exists(Auth::user(), 'updateLastActive')) {
            Auth::user()->updateLastActive();
        }

        return $response;
    }
}
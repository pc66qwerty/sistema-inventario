<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ModuloAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $modulo): Response
    {
        // Verificar si el usuario tiene acceso al módulo
        if (Auth::check() && (Auth::user()->role === 'jefe' || Auth::user()->hasAccessTo($modulo))) {
            return $next($request);
        }

        // Redirigir al dashboard con mensaje de error
        return redirect()->route('dashboard')
            ->with('error', 'No tienes permisos para acceder a este módulo.');
    }
}
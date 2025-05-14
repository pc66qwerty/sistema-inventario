<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Registrar un componente para la visualización de roles de usuario
        Blade::component('user-role-badge', \App\View\Components\UserRoleBadge::class);
        
        // Registrar un componente para mostrar el estado del stock
        Blade::component('stock-badge', \App\View\Components\StockBadge::class);
        
        // Registrar directivas personalizadas para verificar permisos
        Blade::if('role', function ($role) {
            return auth()->check() && auth()->user()->role === $role;
        });
        
        Blade::if('hasAccess', function ($module) {
            return auth()->check() && auth()->user()->hasAccessTo($module);
        });
    }
}
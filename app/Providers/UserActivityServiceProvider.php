<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use App\Models\User;

class UserActivityServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Registrar evento para actualizar last_login_at cuando un usuario inicia sesión
        Event::listen(Login::class, function ($event) {
            $user = $event->user;
            if ($user instanceof User) {
                $user->updateLastLogin();
            }
        });

        // Verificar estado activo del usuario al autenticar
        Auth::provider('users')->validateCredentials = function ($user, $credentials) {
            if (!$user->active) {
                return false;
            }
            
            return app('hash')->check(
                $credentials['password'], $user->getAuthPassword()
            );
        };
    }
}
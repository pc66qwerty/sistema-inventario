<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'active',
        'last_login_at',
        'last_active_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'last_active_at' => 'datetime',
        'active' => 'boolean'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Verifica si el usuario tiene acceso a una funcionalidad específica
     *
     * @param string $module
     * @return bool
     */
    public function hasAccessTo($module)
    {
        // El rol jefe tiene acceso a todo
        if ($this->role === 'jefe') {
            return true;
        }

        // Definir los permisos según el rol
        $permissions = [
            'inventario' => ['inventario'],
            'vendedor' => ['ventas'],
            // Agregar más roles y sus permisos si es necesario
        ];

        // Verificar si el rol del usuario tiene permiso para el módulo
        return isset($permissions[$this->role]) && in_array($module, $permissions[$this->role]);
    }

    /**
     * Actualiza la última vez que el usuario inició sesión
     *
     * @return void
     */
    public function updateLastLogin()
    {
        $this->last_login_at = now();
        $this->save();
    }

    /**
     * Actualiza la última vez que el usuario estuvo activo
     *
     * @return void
     */
    public function updateLastActive()
    {
        $this->last_active_at = now();
        $this->save();
    }
}
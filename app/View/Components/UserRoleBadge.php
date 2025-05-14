<?php

namespace App\View\Components;

use Illuminate\View\Component;

class UserRoleBadge extends Component
{
    /**
     * The user role.
     *
     * @var string
     */
    public $role;

    /**
     * Create a new component instance.
     *
     * @param  string  $role
     * @return void
     */
    public function __construct($role)
    {
        $this->role = $role;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.user-role-badge');
    }

    /**
     * Get the color for the role badge.
     *
     * @return string
     */
    public function color()
    {
        return match ($this->role) {
            'jefe' => 'purple',
            'inventario' => 'green',
            'vendedor' => 'blue',
            default => 'gray',
        };
    }

    /**
     * Get the display name for the role.
     *
     * @return string
     */
    public function displayName()
    {
        return match ($this->role) {
            'jefe' => 'Jefe',
            'inventario' => 'Inventario',
            'vendedor' => 'Vendedor',
            default => ucfirst($this->role),
        };
    }
}
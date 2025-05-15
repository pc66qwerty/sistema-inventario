<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array
     */
    protected $fillable = [
        'nombre',
        'documento',
        'telefono',
        'direccion',
    ];

    /**
     * Obtener las ventas asociadas al cliente.
     */
    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }
}
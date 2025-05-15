<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array
     */
    protected $fillable = [
        'boleta_numero',
        'fecha',
        'cliente', // Asegúrate de que esta línea exista
        'cliente_id',
        'user_id',
        'entregado_por',
        'observaciones',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'fecha' => 'date',
    ];

    /**
     * Obtener el cliente asociado a la venta.
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Obtener el usuario (vendedor) que registró la venta.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtener los detalles de la venta.
     */
    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class);
    }

    /**
     * Obtener el total de la venta calculando desde los detalles
     */
    public function getTotalAttribute()
    {
        return $this->detalles->sum('total');
    }
}
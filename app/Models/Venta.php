<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Venta extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'boleta_numero',
        'fecha',
        'cliente',
        'observaciones',
        'entregado_por'
    ];
    
    protected $casts = [
        'fecha' => 'date',
    ];

    /**
     * Obtiene los detalles de la venta
     */
    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class);
    }
    
    /**
     * Obtiene el total de la venta calculando la suma de los detalles
     */
    public function getTotalAttribute()
    {
        return $this->detalles->sum('total');
    }
}
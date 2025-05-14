<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetalleVenta extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'venta_id',
        'producto_id',
        'descripcion',
        'tipo_arbol',
        'medida',
        'unidad',
        'cantidad',
        'valor_unitario',
        'total'
    ];

    /**
     * Obtiene la venta a la que pertenece este detalle
     */
    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }
    
    /**
     * Obtiene el producto relacionado (si existe)
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
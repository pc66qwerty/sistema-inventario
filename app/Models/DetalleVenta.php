<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array
     */
    protected $fillable = [
        'venta_id',
        'producto_id',
        'descripcion',
        'tipo_arbol',
        'medida',
        'unidad',
        'cantidad',
        'valor_unitario',
        'total',
    ];

    /**
     * Obtener la venta asociada a este detalle.
     */
    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }

    /**
     * Obtener el producto asociado a este detalle.
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
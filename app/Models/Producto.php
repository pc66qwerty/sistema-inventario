<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Producto extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'nombre',
        'tipo_arbol',
        'medida',
        'predio',
        'unidad',
        'stock',
        'precio_unitario',
    ];
    
    /**
     * Obtiene los detalles de venta relacionados con este producto
     */
    public function detallesVenta()
    {
        return $this->hasMany(DetalleVenta::class);
    }
    
    /**
     * Determina si el producto tiene stock bajo
     */
    public function getStockBajoAttribute()
    {
        return $this->stock > 0 && $this->stock <= 10;
    }
    
    /**
     * Determina si el producto está sin stock
     */
    public function getSinStockAttribute()
    {
        return $this->stock <= 0;
    }
    
    /**
     * Calcula el valor total del inventario de este producto
     */
    public function getValorTotalAttribute()
    {
        return $this->stock * $this->precio_unitario;
    }
}
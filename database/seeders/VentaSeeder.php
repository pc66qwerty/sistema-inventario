<?php

use Illuminate\Database\Seeder;
use App\Models\Venta;
use App\Models\DetalleVenta;
use Carbon\Carbon;

class VentaSeeder extends Seeder
{
    public function run(): void
    {
        $venta = Venta::create([
            'boleta_numero' => 'B-0001',
            'fecha' => Carbon::now(),
            'cliente' => 'Pedro López',
            'entregado_por' => 'Administrador Jefe',
        ]);

        DetalleVenta::create([
            'venta_id' => $venta->id,
            'producto_id' => 1, // Asegurate de que exista el producto con ID 1
            'cantidad' => 2,
            'precio' => 50.00,
            'total' => 100.00,
        ]);
    }
}

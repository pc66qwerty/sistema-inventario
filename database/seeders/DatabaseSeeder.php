<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Producto;
use App\Models\Venta;
use App\Models\DetalleVenta;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ Usuario jefe por defecto
        User::firstOrCreate([
            'email' => 'admin@sistema.com',
        ], [
            'name' => 'Administrador Jefe',
            'password' => Hash::make('admin123'),
            'role' => 'jefe',
            'email_verified_at' => now(),
        ]);

        // ✅ Producto de prueba necesario para asociar a la venta
        $producto = Producto::firstOrCreate([
            'nombre' => 'Producto de prueba',
        ], [
            'tipo_arbol' => 'Roble',
            'medida' => '1x2',
            'predio' => 'A1',
            'unidad' => 1.00,
            'stock' => 50,
            'precio_unitario' => 25.00,
        ]);

        // ✅ Venta de prueba
        $venta = Venta::firstOrCreate([
            'boleta_numero' => 'B-0001',
        ], [
            'fecha' => Carbon::now(),
            'cliente' => 'Pedro López',
            'entregado_por' => 'Administrador Jefe',
        ]);

        // ✅ Detalle de la venta con valor_unitario correcto
        DetalleVenta::firstOrCreate([
            'venta_id' => $venta->id,
            'descripcion' => $producto->nombre,
            'tipo_arbol' => $producto->tipo_arbol,
            'medida' => $producto->medida,
            'unidad' => $producto->unidad,
            'cantidad' => 2,
            'valor_unitario' => 25.00,
            'total' => 50.00,
        ]);
    }
}

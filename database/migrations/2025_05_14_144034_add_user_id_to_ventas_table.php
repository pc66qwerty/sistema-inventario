<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Hacer que la columna 'cliente' sea nullable
        Schema::table('ventas', function (Blueprint $table) {
            $table->string('cliente')->nullable()->change();
        });

        // 2. Actualizar los registros existentes para tener valor en 'cliente' 
        // basado en la relación cliente_id
        $ventas = DB::table('ventas')
            ->whereNull('cliente')
            ->whereNotNull('cliente_id')
            ->get();

        foreach ($ventas as $venta) {
            // Obtener el nombre del cliente relacionado
            $cliente = DB::table('clientes')->where('id', $venta->cliente_id)->first();
            
            if ($cliente) {
                // Actualizar el campo cliente con el nombre del cliente
                DB::table('ventas')
                    ->where('id', $venta->id)
                    ->update(['cliente' => $cliente->nombre]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->string('cliente')->nullable(false)->change();
        });
    }
};
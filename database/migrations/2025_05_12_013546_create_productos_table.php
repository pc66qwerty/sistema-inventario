<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('productos', function (Blueprint $table) {
        $table->id();
        $table->string('nombre');
        $table->string('tipo_arbol'); // Ej: Ceiba, Volador, etc.
        $table->string('medida');     // Ej: 1x12x9
        $table->string('predio');     // Ej: Taller
        $table->decimal('unidad', 8, 2)->nullable();   // Ej: docena, piezas
        $table->integer('stock')->default(0);
        $table->decimal('precio_unitario', 10, 2);
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};

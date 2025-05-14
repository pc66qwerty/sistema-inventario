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
    Schema::create('ventas', function (Blueprint $table) {
        $table->id();
        $table->string('boleta_numero')->unique();
        $table->date('fecha');
        $table->string('cliente');
        $table->text('observaciones')->nullable();
        $table->string('entregado_por')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};

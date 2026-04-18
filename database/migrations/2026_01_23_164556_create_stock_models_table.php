<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('upbStock', function (Blueprint $table) {
            $table->bigIncrements('stoID')
                ->comment('Identificador del movimiento de stock');

            $table->unsignedBigInteger('proID')
                ->comment('Producto afectado por el movimiento de stock');

            $table->enum('stoTipo', ['A', 'D'])
                ->comment('Tipo de movimiento: A = Aumento, D = Disminución');

            $table->enum('stoCanal', ['V', 'M'])
                ->comment('Canal del movimiento: V = Venta, M = Movimiento Manual');

            $table->integer('stoViejaCantidad')
                ->comment('Cantidad de stock antes del movimiento');

            $table->integer('stoNuevaCantidad')
                ->comment('Cantidad de stock luego del movimiento');

            $table->timestamps();

            $table->foreign('proID')->references('proID')->on('upbProductos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upbStock');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('upbPedidosDetalles', function (Blueprint $table) {
            $table->id('pdeID');
            $table->unsignedBigInteger('pedID');
            $table->unsignedBigInteger('proID');
            $table->string('pdeNombre');
            $table->decimal('pdePrecioUnitario', 10, 2);
            $table->decimal('pdePrecioPagado', 10, 2);
            $table->decimal('pdePorcentajeDescuento', 5, 2)->nullable();
            $table->integer('pdeCantidad');
            $table->decimal('pdeSubtotal', 10, 2);
            $table->timestamps();

            $table->foreign('pedID')->references('pedID')->on('upbPedidos')->cascadeOnDelete();
            $table->foreign('proID')->references('proID')->on('upbProductos');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('upbPedidosDetalles');
    }
};

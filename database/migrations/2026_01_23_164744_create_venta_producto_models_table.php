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
        Schema::create('upbVentaProductos', function (Blueprint $table) {
            $table->bigIncrements('vprID')
                ->comment('Identificador del ítem de venta');

            $table->unsignedBigInteger('venID')
                ->comment('Venta a la que pertenece el producto');

            $table->unsignedBigInteger('proID')
                ->comment('Producto vendido');

            $table->integer('vprCantidad')
                ->comment('Cantidad del producto en la venta');

            $table->decimal('vprCostoUnitario', 10, 2)
                ->comment('Costo unitario del producto al momento de la venta');

            $table->decimal('vprCostoFinal', 12, 2)
                ->comment('Costo total del producto (cantidad * unitario)');

            $table->timestamps();

            $table->foreign('venID')->references('venID')->on('upbVentas')->onDelete('cascade');
            $table->foreign('proID')->references('proID')->on('upbProductos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upbVentaProductos');
    }
};

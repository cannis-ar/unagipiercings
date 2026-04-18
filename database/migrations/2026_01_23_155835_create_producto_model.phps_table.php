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
        Schema::create('upbProductos', function (Blueprint $table) {
            $table->bigIncrements('proID')
                ->comment('Identificador único del producto de piercing');

            $table->unsignedBigInteger('catID')
                ->comment('Categoría del producto (categoría general)');

            $table->unsignedBigInteger('scaID')
                ->comment('Sub categoría del producto (tipo de producto)')
                ->nullable();

            $table->unsignedBigInteger('matID')
                ->comment('Material principal del producto');

            $table->string('proNombre', 150)
                ->comment('Nombre comercial del producto');

            $table->text('proDescripcion')->nullable()
                ->comment('Descripción detallada del producto');

            $table->decimal('proGrosor', 5, 2)->nullable()
                ->comment('Grosor del piercing en milímetros');

            $table->decimal('proLargo', 5, 2)->nullable()
                ->comment('Largo del piercing en milímetros');

            $table->decimal('proTopTamano', 5, 2)->nullable()
                ->comment('Tamaño del top o adorno frontal en milímetros');

            $table->decimal('proEsferaTamano', 5, 2)->nullable()
                ->comment('Diámetro de la esfera si aplica');

            $table->string('proTipoCierre', 50)->nullable()
                ->comment('Tipo de cierre (rosca interna, externa, clicker, presión, etc)');

            $table->decimal('proDiametro', 5, 2)->nullable()
                ->comment('Diámetro interno del aro si corresponde');

            $table->text('proImagen')->nullable()
                ->comment('Imagen del producto');

            $table->decimal('proPrecio', 10, 2)
                ->comment('Precio de venta del producto');

            $table->decimal('proPorcentajeDescuento', 5, 2)->default(0)
                ->comment('Porcentaje de descuento aplicado al producto');

            $table->integer('proStock')->default(0)
                ->comment('Stock disponible del producto');

            $table->timestamps();

            $table->foreign('catID')->references('catID')->on('upbCategorias');
            $table->foreign('scaID')->references('scaID')->on('upbSubCategorias');
            $table->foreign('matID')->references('matID')->on('upbMateriales');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upbProductos');
    }
};

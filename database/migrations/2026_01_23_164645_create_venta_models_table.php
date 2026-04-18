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
        Schema::create('upbVentas', function (Blueprint $table) {
            $table->bigIncrements('venID')
                ->comment('Identificador único de la venta');

            $table->unsignedBigInteger('usuID')->nullable()
                ->comment('Usuario comprador registrado, null si es venta ocasional');

            $table->string('venPersonaFisica', 150)
                ->comment('Nombre del comprador cuando no hay usuario asociado');

            $table->enum('venEstado', ['C','R','A','PR','EP','EN'])
                ->comment('Estado de la venta: C Consulta, R Rechazado, A Abonado, PR Pendiente Retiro, EP Envío Pendiente, EN Entregado');

            $table->integer('venCantidadProductos')
                ->comment('Cantidad total de productos incluidos en la venta');

            $table->decimal('venCostoFinal', 12, 2)
                ->comment('Costo total final de la venta');

            $table->boolean('venEnvio')->default(0)
                ->comment('Indica si la venta requiere envío: 0 No, 1 Sí');

            $table->timestamps();

            $table->foreign('usuID')->references('usuID')->on('upbUsuarios');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upbVentas');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('upbProductos', function (Blueprint $table) {

            // Indica si el producto tiene descuento comercial activo
            $table->boolean('proDescuento')
                ->after('proPrecio')
                ->default(false)
                ->comment('Indica si el producto tiene descuento comercial aplicado');

            // Indica si el producto admite pago en cuotas
            $table->boolean('proCuotas')
                ->after('proPorcentajeDescuento')
                ->default(false)
                ->comment('Indica si el producto permite pago en cuotas');

            // Indica si aplica descuento por pago con transferencia
            $table->boolean('proDescuentoTransferencia')
                ->after('proCuotas')
                ->default(false)
                ->comment('Indica si el producto tiene descuento por pago con transferencia');

            // Porcentaje de descuento aplicado a transferencias
            $table->decimal('proPorcentajeDescuentoTransferencia', 5, 2)
                ->after('proDescuentoTransferencia')
                ->nullable()
                ->comment('Porcentaje de descuento aplicado si el pago es por transferencia');
        });
    }

    public function down(): void
    {
        Schema::table('upbProductos', function (Blueprint $table) {
            $table->dropColumn([
                'proDescuento',
                'proCuotas',
                'proDescuentoTransferencia',
                'proPorcentajeDescuentoTransferencia',
            ]);
        });
    }
};

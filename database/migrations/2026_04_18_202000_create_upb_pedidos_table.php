<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('upbPedidos', function (Blueprint $table) {
            $table->id('pedID');
            $table->unsignedBigInteger('usuID')->nullable();
            // Las FKs circulares (opeID, traID, comID, envID) se agregan después
            $table->enum('pedEstado', ['PR', 'CA', 'PE', 'AB'])->default('PE');
            $table->enum('pedPago', ['MP', 'TR']);
            $table->enum('pedEntrega', ['EN', 'RE', 'EP']);
            $table->decimal('pedTotal', 10, 2);
            $table->decimal('pedTotalTransferencia', 10, 2)->nullable();
            $table->timestamps();

            $table->foreign('usuID')
                ->references('usuID')
                ->on('upbUsuarios')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('upbPedidos');
    }
};

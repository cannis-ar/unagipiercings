<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('upbOperaciones', function (Blueprint $table) {
            $table->id('opeID');
            $table->unsignedBigInteger('usuID');
            $table->unsignedBigInteger('traID')->nullable();
            $table->unsignedBigInteger('pedID');
            // comID se agrega en migración separada (después de upbComprobantes)
            $table->enum('opeEstado', ['CA', 'PE', 'CO'])->default('PE');
            $table->enum('opeTipo', ['MP', 'TR']);
            $table->decimal('opeMonto', 10, 2);
            $table->decimal('opeMontoComision', 10, 2)->nullable();
            $table->decimal('opeIVA', 10, 2)->nullable();
            $table->decimal('opeIIBB', 10, 2)->nullable();
            $table->timestamp('opeFecAlta')->nullable();
            $table->timestamp('opeFecAcreditacion')->nullable();
            $table->timestamps();

            $table->foreign('usuID')->references('usuID')->on('upbUsuarios');
            $table->foreign('traID')->references('traID')->on('upbTransacciones')->nullOnDelete();
            $table->foreign('pedID')->references('pedID')->on('upbPedidos');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('upbOperaciones');
    }
};

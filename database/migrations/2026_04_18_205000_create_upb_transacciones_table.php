<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('upbTransacciones', function (Blueprint $table) {
            $table->id('traID');
            $table->unsignedBigInteger('pedID')->nullable();
            $table->enum('traFinanciador', ['MP']);
            $table->enum('traEstado', ['CA', 'PE', 'CO'])->default('PE');
            $table->decimal('traMonto', 10, 2);
            $table->decimal('traMontoComision', 10, 2)->nullable();
            $table->decimal('traIVA', 10, 2)->nullable();
            $table->decimal('traIIBB', 10, 2)->nullable();
            $table->timestamp('traFecAlta')->nullable();
            $table->timestamp('traFecAcreditacion')->nullable();
            $table->json('traWebhook')->nullable();
            $table->timestamps();

            $table->foreign('pedID')->references('pedID')->on('upbPedidos')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('upbTransacciones');
    }
};

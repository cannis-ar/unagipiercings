<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('upbEnvios', function (Blueprint $table) {
            $table->id('envID');
            $table->unsignedBigInteger('pedID');
            $table->string('envDireccion', 255)->nullable();
            $table->enum('envRetiro', ['S', 'N'])->default('N');
            $table->timestamps();

            $table->foreign('pedID')->references('pedID')->on('upbPedidos')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('upbEnvios');
    }
};

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
        Schema::create('upbCategorias', function (Blueprint $table) {
            $table->bigIncrements('catID')
                ->comment('Identificador único de la categoría de piercing');

            $table->string('catNombre', 100)
                ->comment('Nombre comercial de la categoría (ej: Septum, Helix, Labret)');

            $table->text('catDescripcion')->nullable()
                ->comment('Descripción funcional o estética de la categoría');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upbCategorias');
    }
};

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
        Schema::create('upbSubCategorias', function (Blueprint $table) {
            $table->bigIncrements('scaID')
                ->comment('Identificador único de la subcategoría');

            $table->unsignedBigInteger('catID')
                ->comment('Categoría padre a la que pertenece la subcategoría');

            $table->string('scaNombre', 100)
                ->comment('Nombre de la subcategoría');

            $table->text('scaDescripcion')->nullable()
                ->comment('Descripción de la subcategoría');

            $table->timestamps();

            $table->foreign('catID')
                ->references('catID')
                ->on('upbCategorias')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upbSubCategorias');
    }
};

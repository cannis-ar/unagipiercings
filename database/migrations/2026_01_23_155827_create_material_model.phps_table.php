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
        Schema::create('upbMateriales', function (Blueprint $table) {
            $table->bigIncrements('matID')
                ->comment('Identificador único del material');

            $table->string('matNombre', 100)
                ->comment('Nombre del material (Titanio grado implante, Acero quirúrgico, Oro, etc)');

            $table->text('matDescripcion')->nullable()
                ->comment('Descripción técnica y estética del material');

            $table->text('matCuidados')->nullable()
                ->comment('Cuidados específicos recomendados para este material');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upbMateriales');
    }
};

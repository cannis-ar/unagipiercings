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
        Schema::create('upbUsuarios', function (Blueprint $table) {
            $table->unsignedBigInteger('usuID')
                ->comment('Identificador del usuario, FK directa con users.id');

            $table->string('usuNombre', 100)
                ->comment('Nombre del usuario comprador');

            $table->string('usuApellido', 100)
                ->comment('Apellido del usuario comprador');

            $table->string('usuCelular', 30)->nullable()
                ->comment('Teléfono de contacto del usuario');

            $table->date('usuFechaNacimiento')->nullable()
                ->comment('Fecha de nacimiento del usuario');

            $table->timestamps();

            $table->primary('usuID');
            $table->foreign('usuID')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upbUsuarios');
    }
};

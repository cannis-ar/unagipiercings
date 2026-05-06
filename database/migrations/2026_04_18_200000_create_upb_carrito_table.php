<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('upbCarrito', function (Blueprint $table) {
            $table->id('carID');
            $table->unsignedBigInteger('usuID')->nullable();
            $table->string('carToken', 64)->unique()->index();
            $table->enum('carEstado', ['AC', 'IN', 'CO'])->default('AC');
            $table->json('carProductos')->nullable();
            $table->decimal('carTotal', 10, 2)->default(0);
            $table->timestamps();

            $table->foreign('usuID')
                ->references('usuID')
                ->on('upbUsuarios')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('upbCarrito');
    }
};

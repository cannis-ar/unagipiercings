<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('upbComprobantes', function (Blueprint $table) {
            $table->id('comID');
            $table->unsignedBigInteger('pedID');
            $table->unsignedBigInteger('opeID');
            $table->string('comRuta', 500);
            $table->timestamp('comFecPago')->nullable();
            $table->timestamps();

            $table->foreign('pedID')->references('pedID')->on('upbPedidos');
            $table->foreign('opeID')->references('opeID')->on('upbOperaciones');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('upbComprobantes');
    }
};

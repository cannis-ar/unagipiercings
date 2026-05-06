<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('upbParametros', function (Blueprint $table) {
            $table->id('parID');
            $table->string('parNombre', 100)->unique();
            $table->string('parValor', 255);
            $table->timestamps();
        });

        DB::table('upbParametros')->insert([
            ['parNombre' => 'DESCUENTO_TRANSFERENCIA', 'parValor' => '50', 'created_at' => now(), 'updated_at' => now()],
            ['parNombre' => 'COSTO_ENVIO_INTERNO',     'parValor' => '5000', 'created_at' => now(), 'updated_at' => now()],
            ['parNombre' => 'COSTO_ENVIO_RETIRO',      'parValor' => '0', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('upbParametros');
    }
};

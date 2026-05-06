<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('upbPedidos', function (Blueprint $table) {
            $table->unsignedBigInteger('opeID')->nullable()->after('usuID');
            $table->unsignedBigInteger('traID')->nullable()->after('opeID');
            $table->unsignedBigInteger('comID')->nullable()->after('traID');
            $table->unsignedBigInteger('envID')->nullable()->after('comID');

            $table->foreign('opeID')->references('opeID')->on('upbOperaciones')->nullOnDelete();
            $table->foreign('traID')->references('traID')->on('upbTransacciones')->nullOnDelete();
            $table->foreign('comID')->references('comID')->on('upbComprobantes')->nullOnDelete();
            $table->foreign('envID')->references('envID')->on('upbEnvios')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('upbPedidos', function (Blueprint $table) {
            $table->dropForeign(['opeID']);
            $table->dropForeign(['traID']);
            $table->dropForeign(['comID']);
            $table->dropForeign(['envID']);
            $table->dropColumn(['opeID', 'traID', 'comID', 'envID']);
        });
    }
};

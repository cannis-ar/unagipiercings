<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('upbOperaciones', function (Blueprint $table) {
            $table->unsignedBigInteger('comID')->nullable()->after('pedID');
            $table->foreign('comID')->references('comID')->on('upbComprobantes')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('upbOperaciones', function (Blueprint $table) {
            $table->dropForeign(['comID']);
            $table->dropColumn('comID');
        });
    }
};

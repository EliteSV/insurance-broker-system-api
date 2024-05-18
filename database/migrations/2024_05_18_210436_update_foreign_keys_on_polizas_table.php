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
        Schema::table('polizas', function (Blueprint $table) {
            $table->dropForeign(['cliente_id']);
            $table->dropForeign(['aseguradora_id']);
            $table->dropForeign(['tipo_poliza_id']);

            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('restrict');
            $table->foreign('aseguradora_id')->references('id')->on('aseguradoras')->onDelete('restrict');
            $table->foreign('tipo_poliza_id')->references('id')->on('tipo_polizas')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('polizas', function (Blueprint $table) {
            $table->dropForeign(['cliente_id']);
            $table->dropForeign(['aseguradora_id']);
            $table->dropForeign(['tipo_poliza_id']);

            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
            $table->foreign('aseguradora_id')->references('id')->on('aseguradoras')->onDelete('cascade');
            $table->foreign('tipo_poliza_id')->references('id')->on('tipo_polizas')->onDelete('cascade');
        });
    }
};

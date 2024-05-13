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
        Schema::create('polizas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('estado');
            $table->decimal('monto', 10, 2);
            $table->integer('cuotas');
            $table->json('detalles');
            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('aseguradora_id');
            $table->unsignedBigInteger('tipo_poliza_id');
            $table->timestamps();

            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
            $table->foreign('aseguradora_id')->references('id')->on('aseguradoras')->onDelete('cascade');
            $table->foreign('tipo_poliza_id')->references('id')->on('tipo_polizas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('polizas');
    }
};

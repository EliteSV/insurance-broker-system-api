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
        Schema::create('documentos_clientes', function (Blueprint $table) {
            $table->id();
            $table->string('url', 255);
            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('tipo_documento_id');
            $table->timestamps();

            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
            $table->foreign('tipo_documento_id')->references('id')->on('tipos_documentos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentos_clientes');
    }
};

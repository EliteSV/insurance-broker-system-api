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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->decimal('cantidad', 10, 2);
            $table->string('fecha_vencimiento', 255)->nullable();
            $table->string('comprobante', 255)->nullable();
            $table->string('estado', 255)->nullable();
            $table->string('fecha_pagado', 255)->nullable();
            $table->unsignedBigInteger('vigencia_poliza_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};

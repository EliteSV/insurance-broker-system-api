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
        Schema::create('vigencia_polizas', function (Blueprint $table) {
            $table->id();
            $table->string('fecha_inicio', 255);
            $table->string('fecha_vencimiento', 255);
            $table->unsignedBigInteger('poliza_id');
            $table->timestamps();

            $table->foreign('poliza_id')->references('id')->on('polizas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vigencia_polizas');
    }
};

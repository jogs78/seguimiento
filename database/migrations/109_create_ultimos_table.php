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
        Schema::create('ultimos', function (Blueprint $table) {
            $table->id();
            $table->integer('portada');
            $table->integer('agradecimientos');
            $table->integer('resumen');
            $table->integer('indice');
            $table->integer('introduccion');
            $table->integer('problemas');
            $table->integer('objetivos');
            $table->integer('justificacion');
            $table->integer('marco_teorico');
            $table->integer('procedimiento');
            $table->integer('resultados');
            $table->integer('conclusiones');
            $table->integer('competencias');
            $table->integer('fuentes');
            //$table->string('ruta'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ultimos');
    }
};

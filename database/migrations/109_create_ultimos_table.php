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
            $table->foreignId('estudiante_id')->nullable()->default(null)->constrained('estudiantes');
            $table->integer('portada_externo')->nullable()->default(null);
            $table->integer('agradecimientos_externo')->nullable()->default(null);
            $table->integer('resumen_externo')->nullable()->default(null);
            $table->integer('indice_externo')->nullable()->default(null);
            $table->integer('introduccion_externo')->nullable()->default(null);
            $table->integer('problemas_externo')->nullable()->default(null);
            $table->integer('objetivos_externo')->nullable()->default(null);
            $table->integer('justificacion_externo')->nullable()->default(null);
            $table->integer('marco_teorico_externo')->nullable()->default(null);
            $table->integer('procedimiento_externo')->nullable()->default(null);
            $table->integer('resultados_externo')->nullable()->default(null);
            $table->integer('conclusiones_externo')->nullable()->default(null);
            $table->integer('competencias_externo')->nullable()->default(null);
            $table->integer('fuentes_externo')->nullable()->default(null);
            $table->float  ('promedio_externo')->nullable()->default(null);
            $table->text('comentarios_externo')->nullable()->default(null);

            $table->integer('portada_interno')->nullable()->default(null);
            $table->integer('agradecimientos_interno')->nullable()->default(null);
            $table->integer('resumen_interno')->nullable()->default(null);
            $table->integer('indice_interno')->nullable()->default(null);
            $table->integer('introduccion_interno')->nullable()->default(null);
            $table->integer('problemas_interno')->nullable()->default(null);
            $table->integer('objetivos_interno')->nullable()->default(null);
            $table->integer('justificacion_interno')->nullable()->default(null);
            $table->integer('marco_teorico_interno')->nullable()->default(null);
            $table->integer('procedimiento_interno')->nullable()->default(null);
            $table->integer('resultados_interno')->nullable()->default(null);
            $table->integer('conclusiones_interno')->nullable()->default(null);
            $table->integer('competencias_interno')->nullable()->default(null);
            $table->integer('fuentes_interno')->nullable()->default(null);
            $table->float  ('promedio_interno')->nullable()->default(null);
            $table->text('comentarios_interno')->nullable()->default(null);
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

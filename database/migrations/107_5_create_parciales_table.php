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
        Schema::create('parciales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estudiante_id')->nullable()->default(null)->constrained('estudiantes');
            $table->float  ('promedio_parcial')->nullable()->default(null); 
            $table->enum('consecutivo',['primer','segundo'])->nullable()->default(null);

            $table->integer('puntualidad_externo')->nullable()->default(null);
            $table->integer('equipo_externo')->nullable()->default(null);
            $table->integer('iniciativa_externo')->nullable()->default(null);
            $table->integer('mejoras_externo')->nullable()->default(null); 
            $table->integer('objetivos_externo')->nullable()->default(null); 
            $table->integer('orden_externo')->nullable()->default(null); 
            $table->integer('liderazgo_externo')->nullable()->default(null); 
            $table->integer('conocimiento_externo')->nullable()->default(null);
            $table->integer('etico_externo')->nullable()->default(null); 
            $table->float  ('promedio_externo')->nullable()->default(null);
            $table->text('comentarios_externo')->nullable()->default(null);



            $table->integer('puntualidad_interno')->nullable()->default(null);
            $table->integer('conocimiento_interno')->nullable()->default(null);
            $table->integer('equipo_interno')->nullable()->default(null);
            $table->integer('dedicado_interno')->nullable()->default(null); 
            $table->integer('orden_interno')->nullable()->default(null); 
            $table->integer('mejoras_interno')->nullable()->default(null); 
            $table->float  ('promedio_interno')->nullable()->default(null); 
            $table->text('comentarios_interno')->nullable()->default(null);
            $table->timestamp('califico_externo')->nullable()->default(null);
            $table->timestamp('califico_interno')->nullable()->default(null);
            //$table->morphs('parciales');
            $table->string('ruta')->nullable()->default(null); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parcials');
    }
};

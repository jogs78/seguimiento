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
        Schema::create('estudiantes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellido_paterno');
            $table->string('apellido_materno');
            $table->string('correo_electronico');
            $table->string('numero_de_control');
            $table->foreignId('proyecto_id');//->constrained('proyectos');
            $table->string('telefono');
            $table->foreignId('carrera_id')->constrained('carreras');
            $table->string('direccion');
            $table->enum('institucion_seguiridad_social',['IMSS','ISSSTE','OTROS']);
            $table->string('numero_de_seguridad_social');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estudiantes');
    }
};

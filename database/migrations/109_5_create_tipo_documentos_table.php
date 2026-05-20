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
        Schema::create('tipo_documentos', function (Blueprint $table) {
            $table->id();

            // Nombre del documento
            $table->string('nombre');

            // Descripción opcional
            $table->text('descripcion')->nullable();

            // Tipos permitidos: pdf, docx, jpg...
            $table->json('extensiones_permitidas')->nullable();

            // Tamaño máximo en MB
            $table->integer('tamano_maximo_mb')->default(10);

            // Si es obligatorio
            $table->boolean('obligatorio')->default(true);

            // Permitir URL además de archivo
            $table->boolean('permite_url')->default(true);

            // Activo/inactivo
            $table->boolean('activo')->default(true);

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_documentos');
    }
};

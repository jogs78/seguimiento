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
        Schema::create('documento_estudiantes', function (Blueprint $table) {
            $table->id();

            // Relación con estudiante
            $table->foreignId('estudiante_id')
                ->constrained('estudiantes')
                ->onDelete('cascade');

            // Tipo de documento
            $table->foreignId('tipo_documento_id')
                ->constrained('tipo_documentos')
                ->onDelete('cascade');

            // Nombre original del archivo
            $table->string('nombre_original')->nullable();

            // Ruta guardada por Storage
            $table->string('ruta_archivo')->nullable();

            // URL externa (OneDrive, Drive, etc.)
            $table->text('url_documento')->nullable();

            // archivo | url
            $table->enum('tipo_subida', ['archivo', 'url']);

            // pdf, docx...
            $table->string('extension')->nullable();

            // Peso en bytes
            $table->bigInteger('peso_bytes')->nullable();

            // application/pdf...
            $table->string('mime_type')->nullable();

            // Fecha de subida
            $table->timestamp('subido_en')->useCurrent();

            // Última actualización manual
            $table->timestamp('actualizado_en')->nullable();

            // Comentarios del coordinador
            $table->text('observaciones')->nullable();

            // Evitar duplicados
            $table->unique(['estudiante_id', 'tipo_documento_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documento_estudiantes');
    }
};

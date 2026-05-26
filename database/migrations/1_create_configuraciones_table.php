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
        Schema::create('configuraciones', function (Blueprint $table) {
            $table->id();
            $table->string('variable');
            $table->string('valor');
            $table->enum('tipo',["numero","cadena","bd"])->default('cadena');  
            $table->string('tabla')->nullable()->default(null);
            $table->string('campo')->nullable()->default(null);
            
            $table->foreignId('carrera_id')
            ->nullable()
            ->default(null)
            ->constrained('carreras')
            ->onDelete('cascade');

            // evita duplicados
            $table->unique(['variable', 'carrera_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuraciones');
    }
};

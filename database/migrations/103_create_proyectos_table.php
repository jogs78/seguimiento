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
        Schema::create('proyectos', function (Blueprint $table) {
            $table->id();
            $table->string("num_registro")->nullable()->default(null);
            $table->string("nombre")->nullable()->default(null);
            $table->string("objetivo_general")->nullable()->default(null);
            $table->string("lugar")->nullable()->default(null);
            $table->string("informacion")->nullable()->default(null);
            $table->string("justificacion")->nullable()->default(null);
            $table->enum('origen',["Banco de Proyectos","Propuesta propia","Trabajador"]);
            $table->foreignId('asesor_id')->nullable()->default(null)->constrained('asesores');
            $table->foreignId('externo_id')->nullable()->default(null)->constrained('externos');
            $table->foreignId('empresa_id')->nullable()->default(null)->constrained('empresas');
            $table->foreignId('periodo_id')->nullable()->default(null)->constrained('periodos');
            $table->boolean('fuera_de_tiempo')->nullable()->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyectos');
    }
};

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
        Schema::create('periodos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->date('fecha_inicio');
            $table->date('fecha_final');
            $table->date('fecha_inicio_1er_reporte');
            $table->date('fecha_final_1er_reporte');
            $table->date('fecha_inicio_2do_reporte');
            $table->date('fecha_final_2do_reporte');
            $table->date('fecha_inicio_reporte_final');
            $table->date('fecha_final_reporte_final');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periodos');
    }
};

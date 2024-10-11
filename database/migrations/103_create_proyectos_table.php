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
            $table->string("nombre");
            $table->string("objetivo_general")->nullable()->default(null);
            $table->string("lugar")->nullable()->default(null);
            $table->string("informacion")->nullable()->default(null);
            $table->string("justificacion")->nullable()->default(null);
            //$table->string('asesor_externo');
            //$table->string('puesto_asesor');
            $table->foreignId('asesor_id')->nullable()->default(null)->constrained('asesores');
            $table->foreignId('empresa_id')->nullable()->default(null)->constrained('empresas');
            $table->foreignId('periodo_id')->nullable()->default(null)->constrained('periodos');
//            $table->bigInteger("asesor_id")->unsinged();
//            $table->foreign("asesor_id")->refences("id")->on("asesores");
//            $table->bigInteger("empresa_id")->unsinged();
//            $table->foreign("empresa_id")->refences("id")->on("empresas");
//            $table->bigInteger("periodo_id")->unsinged();
//            $table->foreign("periodo_id")->refences("id")->on("periodos");
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

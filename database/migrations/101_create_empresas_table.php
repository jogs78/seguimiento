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
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('giro');
            $table->string('rfc');
            $table->string('direccion');
            $table->string('telefono');
            $table->string('correo');
            $table->string('titular');
            $table->string('puesto_titular');
            $table->string('asesor_externo');
            $table->string('puesto_asesor');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};

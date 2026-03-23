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
        Schema::create('carreras', function (Blueprint $table) {
            $table->id();
            $table->string("nombre");
            $table->string("clave")->nullable();
            $table->string("modalidad")->nullable();
            //agregar la relación con el coordinador en formato de llave foranea
            $table->foreignId('coordinador_id')->nullable()->default(null)->constrained('coordinadores');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carreras');
    }
};

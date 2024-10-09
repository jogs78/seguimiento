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
        Schema::create('seguimientos', function (Blueprint $table) {
            $table->id();
            $table->integer('primero_promedio');
            $table->integer('segundo_promedio');
            $table->integer('final_promedio');
            $table->foreignId('primero_id')->nullable()->default(null)->constrained('primeros');
            $table->foreignId('segundo_id')->nullable()->default(null)->constrained('segundos');
            $table->foreignId('final_id')->nullable()->default(null)->constrained('ultimos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seguimientos');
    }
};

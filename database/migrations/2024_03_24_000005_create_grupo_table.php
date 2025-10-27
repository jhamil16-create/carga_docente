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
        Schema::create('grupo', function (Blueprint $table) {
            $table->id('grupo_id');
            $table->foreignId('materia_id')->constrained('materia', 'materia_id');
            $table->integer('numero_grupo');
            $table->integer('capacidad');
            $table->string('semestre', 20);
            $table->integer('gestion');

            // Asegurar que no haya grupos duplicados para la misma materia, semestre y gestión
            $table->unique(['materia_id', 'numero_grupo', 'semestre', 'gestion']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grupo');
    }
};
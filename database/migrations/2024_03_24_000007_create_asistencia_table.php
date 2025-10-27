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
        Schema::create('asistencia', function (Blueprint $table) {
            $table->id('asistencia_id');
            $table->foreignId('asignacion_id')->constrained('asignacion', 'asignacion_id');
            $table->foreignId('grupo_id')->constrained('grupo', 'grupo_id');
            $table->timestamp('fecha_asistencia');
            $table->boolean('estado_asistencia');
            $table->text('observaciones')->nullable();

            // Asegurar que no haya múltiples registros de asistencia para la misma asignación y fecha
            $table->unique(['asignacion_id', 'fecha_asistencia']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistencia');
    }
};
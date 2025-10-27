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
        Schema::create('asignacion', function (Blueprint $table) {
            $table->id('asignacion_id');
            $table->foreignId('docente_id')->constrained('docente', 'docente_id');
            $table->foreignId('grupo_id')->constrained('grupo', 'grupo_id');
            $table->timestamp('fecha_asignacion')->useCurrent();

            // Asegurar que un docente no tenga múltiples asignaciones al mismo grupo
            $table->unique(['docente_id', 'grupo_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignacion');
    }
};
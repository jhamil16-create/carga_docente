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
        Schema::create('asignaciones', function (Blueprint $table) {
            $table->id('asignacion_id');
            $table->unsignedBigInteger('docente_id')->notNull();
            $table->unsignedBigInteger('grupo_id')->notNull();
            $table->unsignedBigInteger('aula_id')->notNull();
            $table->unsignedBigInteger('horario_id')->notNull();
            $table->date('fecha_asignacion')->default(DB::raw('CURRENT_DATE'));
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('docente_id')->references('docente_id')->on('docentes');
            $table->foreign('grupo_id')->references('grupo_id')->on('grupos');
            $table->foreign('aula_id')->references('aula_id')->on('aulas');
            $table->foreign('horario_id')->references('horario_id')->on('horarios');
            
            // Índices únicos para evitar conflictos
            $table->unique(['aula_id', 'horario_id'], 'unique_aula_horario');
            $table->unique(['docente_id', 'horario_id'], 'unique_docente_horario');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignaciones');
    }
};

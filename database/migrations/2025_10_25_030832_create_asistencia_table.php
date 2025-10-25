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
            $table->unsignedBigInteger('asignacion_id')->notNull();
            $table->date('fecha')->notNull();
            $table->time('hora_registro')->notNull();
            $table->enum('estado', ['presente', 'ausente', 'tardanza'])->notNull();
            $table->text('observaciones')->nullable();
            $table->timestamps();
            
            // Foreign key
            $table->foreign('asignacion_id')->references('asignacion_id')->on('asignaciones');
            
            // Índice único para evitar registros duplicados
            $table->unique(['asignacion_id', 'fecha'], 'unique_asignacion_fecha');
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

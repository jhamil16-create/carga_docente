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
        Schema::create('grupos', function (Blueprint $table) {
            $table->id('grupo_id');
            $table->unsignedBigInteger('materia_id')->notNull();
            $table->string('nombre_grupo', 50)->notNull();
            $table->integer('capacidad_maxima')->notNull();
            $table->timestamps();
            
            $table->foreign('materia_id')->references('materia_id')->on('materias');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grupos');
    }
};

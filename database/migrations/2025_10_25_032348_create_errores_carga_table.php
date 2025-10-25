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
        Schema::create('errores_carga', function (Blueprint $table) {
            $table->id('error_id');
            $table->unsignedBigInteger('carga_id')->notNull();
            $table->integer('numero_fila')->notNull();
            $table->string('campo_error', 100)->nullable();
            $table->text('descripcion_error')->notNull();
            $table->json('datos_fila')->nullable();
            $table->timestamps();
            
            // Foreign key
            $table->foreign('carga_id')->references('carga_id')->on('carga_masiva');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('errores_carga');
    }
};

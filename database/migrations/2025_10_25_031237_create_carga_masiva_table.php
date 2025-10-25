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
        Schema::create('carga_masiva', function (Blueprint $table) {
            $table->id('carga_id');
            $table->unsignedBigInteger('usuario_id')->notNull();
            $table->string('tipo_carga', 50)->notNull();
            $table->string('nombre_archivo', 255)->notNull();
            $table->timestamp('fecha_carga')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('registros_procesados')->default(0);
            $table->integer('registros_exitosos')->default(0);
            $table->integer('registros_fallidos')->default(0);
            $table->enum('estado', ['procesando', 'completado', 'fallido'])->default('procesando');
            $table->timestamps();
            
            // Foreign key
            $table->foreign('usuario_id')->references('usuario_id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carga_masiva');
    }
};

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
        Schema::create('bitacora', function (Blueprint $table) {
            $table->id('bitacora_id');
            $table->unsignedBigInteger('usuario_id')->nullable();
            $table->string('accion', 100)->notNull();
            $table->string('tabla_afectada', 50)->nullable();
            $table->unsignedBigInteger('registro_id')->nullable();
            $table->json('datos_anteriores')->nullable();
            $table->json('datos_nuevos')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('fecha_accion')->default(DB::raw('CURRENT_TIMESTAMP'));
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
        Schema::dropIfExists('bitacora');
    }
};

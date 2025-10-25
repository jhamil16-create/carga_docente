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
        Schema::create('docentes', function (Blueprint $table) {
            $table->id('docente_id');
            $table->unsignedBigInteger('usuario_id')->unique();
            $table->string('especialidad', 100)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->date('fecha_registro')->default(DB::raw('CURRENT_DATE'));
            $table->timestamps();
            
            $table->foreign('usuario_id')->references('usuario_id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('docentes');
    }
};

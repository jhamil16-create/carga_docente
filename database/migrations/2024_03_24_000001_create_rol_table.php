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
        Schema::create('rol', function (Blueprint $table) {
            $table->id('rol_id');
            $table->string('nombre_rol', 50)->unique();
        });

        // Insertar roles por defecto
        DB::table('rol')->insert([
            ['nombre_rol' => 'administrador'],
            ['nombre_rol' => 'docente']
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rol');
    }
};
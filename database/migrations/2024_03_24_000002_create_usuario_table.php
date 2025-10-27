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
        Schema::create('usuario', function (Blueprint $table) {
            $table->id('usuario_id');
            $table->foreignId('rol_id')->constrained('rol', 'rol_id');
            $table->string('nombre_usuario', 100);
            $table->string('email_institucional')->unique();
            $table->string('contraseña_hash');
            $table->boolean('activo')->default(true);
            $table->rememberToken();
            $table->string('token_recuperacion')->nullable();
            $table->timestamp('expiracion_token_recuperacion')->nullable();
        });

        // Crear usuario administrador por defecto
        DB::table('usuario')->insert([
            'rol_id' => 1, // ID del rol administrador
            'nombre_usuario' => 'Administrador',
            'email_institucional' => 'admin@umss.edu.bo',
            'contraseña_hash' => Hash::make('admin123'),
            'activo' => true
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario');
    }
};
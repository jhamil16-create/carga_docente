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
        Schema::table('users', function (Blueprint $table) {
            // Renombrar id a usuario_id
            $table->renameColumn('id', 'usuario_id');
            
            // Agregar rol_id
            $table->unsignedBigInteger('rol_id')->after('usuario_id');
            
            // Agregar codigo_usuario
            $table->string('codigo_usuario', 6)->unique()->after('rol_id');
            
            // Modificar campos existentes
            $table->string('name', 100)->change();
            $table->renameColumn('name', 'nombre');
            
            // Agregar apellido
            $table->string('apellido', 100)->after('nombre');
            
            // Modificar email
            $table->string('email', 150)->change();
            $table->renameColumn('email', 'email_institucional');
            
            // Modificar password
            $table->renameColumn('password', 'contraseña_hash');
            
            // Agregar activo
            $table->boolean('activo')->default(true)->after('contraseña_hash');
            
            // Agregar foreign key
            $table->foreign('rol_id')->references('rol_id')->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['rol_id']);
            $table->dropColumn(['rol_id', 'codigo_usuario', 'apellido', 'activo']);
            $table->renameColumn('usuario_id', 'id');
            $table->renameColumn('nombre', 'name');
            $table->renameColumn('email_institucional', 'email');
            $table->renameColumn('contraseña_hash', 'password');
            $table->string('name', 255)->change();
            $table->string('email', 255)->change();
        });
    }
};
